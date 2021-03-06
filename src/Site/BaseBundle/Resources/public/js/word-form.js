$.fn.word_form = function () {
  var $f = this;
  var $obj;

  $obj = {
    init: function () {
      $('.word-translation-container', $f).word_form_translation();
      $('.word-image-container', $f).word_form_image();
    }
  };

  $obj.init();

  return this;
}

$.fn.word_form_translation = function () {
  var $h = this;
  var source_container = $('.word-source-container', $h);
  var source_word = $('input[name*=source]', source_container);
  var translated_word = $('input[name*=target]', $h);
  var searcher = $('.finds-translation', source_container);
  var translations_container = $('.translations-target', source_container);
  var loading_class = 'loading';
  var $obj;

  $obj = {
    init: function () {
      searcher.on('click', $obj.search);
      translations_container.delegate('th span', 'click', $obj.use_translation);
    },
    search: function () {
      translations_container.html('');
      translated_word.val('');

      if (!source_word.val().length) return false;
      $.ajax({
        url: searcher.attr('href'),
        type: 'post',
        dataType: 'json',
        data: { word: source_word.val() },
        beforeStart: function () {
          searcher.button('loading');
          translations_container.addClass(loading_class);
        },
        success: $obj.render_translations,
        onComplete: function () {
          searcher.button('reset');
          translations_container.removeClass(loading_class)
        }
      });
    },
    render_translations: function (r) {
      var $list = $('<table/>'),
          row = $('<tr><td class="relevancy"><i><b/></i></td><th/><td class="reverse-words"/></tr>'),
          word = $('<span/>');
      var relevancies = [];
      for (var key in r.translations) {
        var re = r.translations[key].relevancy;
        relevancies.push($.isArray(re) ? re[0] : re);
      }
      var max_relevancy = relevancies.sort().pop();

      for (var key in r.translations) {
        var translation = r.translations[key];
        var relevancy = $.isArray(translation.relevancy) ? translation.relevancy[0] : translation.relevancy;
        var $row = row.clone();
        $('.relevancy b', $row).width(Math.floor(relevancy / max_relevancy * 100) + '%');
        $('th', $row).append(word.clone().text(translation.word));
        var words = [];
        $.each(translation.reverse_values, function() {
          words.push(word.clone().text(this).get(0).outerHTML);
        })
        $('.reverse-words', $row).append(words.join(', '));

        $list.append($row);
      }
      $list.appendTo(translations_container);
    },
    use_translation: function () {
      translated_word.val($(this).text());
    }
  };

  $obj.init();

  return this;
};

$.fn.word_form_image = function () {
  var $h = this;
  var $obj;
  var $image;
  var field_x;
  var field_y;
  var field_w;
  var field_h;
  var $accordion, $accordion_flickr_holder, $accordion_flickr_inner;

  $obj = {
    init: function () {
      $image = $('.crop-container img', $h);
      $accordion = $('#image_accordion', $h);
      $accordion_flickr_holder = $('#image_select-from-web', $accordion);
      $accordion_flickr_inner = $('.accordion-inner', $accordion_flickr_holder);
      $(':file', $h).on('change', $obj.submit_remote);
      if ($image.length) {
        $obj.load_jcrop();
        field_x = $("[name*=crop_x]", $h);
        field_y = $("[name*=crop_y]", $h);
        field_w = $("[name*=crop_w]", $h);
        field_h = $("[name*=crop_h]", $h);
      }
      $("#image_select-from-web_heading", $accordion).on('click', $obj.load_flickr_images);
      $accordion_flickr_holder.delegate('.accordion-inner:not(.loading) .flickr-images-list a', 'click', $obj.use_remote_image);
    },
    use_remote_image: function(e) {
      e.preventDefault();
      var $link = $(this);
      var $img = $('img', $link);
      $.ajax({
        url: $link.attr('href'),
        type: 'post',
        data: { url: $img.attr('data-full-image-url') },
        beforeSend : function() {
          $accordion_flickr_inner.addClass('loading');
        },
        complete: function () {
          $accordion_flickr_inner.removeClass('loading');
        },
        success: function(r) {
          $h.html($(r).html());
          $obj.init();
        }
      });
    },
    load_flickr_images: function () {
      var $list = $('.flickr-images-list', $accordion);
      if ($accordion_flickr_inner.is('.fetching-data') || $accordion_flickr_inner.is('.loaded')) return true;
      $.ajax({
        url: $list.data('source'),
        beforeStart: function () {
          $list.addClass('fetching-data');
        },
        success: function (r) {
          $list.html($(r).html());
        },
        complete: function () {
          $accordion_flickr_inner.removeClass('loading fetching-data').addClass('loaded');
        }
      });

    },
    load_jcrop: function () {
      setTimeout(function () {
        console.log('wait for image...');
        if ($image.width() > 16) {
          $image.Jcrop({
            aspectRatio: 1,
            onChange: $obj.update_crop_fields,
            onSelect: $obj.update_crop_fields,
            setSelect: $obj.getInitialSelect()
          });
        }
        else {
          $obj.load_jcrop();
        }
      }, 300);
    },
    update_crop_fields: function (c) {
      field_x.val(c.x);
      field_y.val(c.y);
      field_w.val(c.w);
      field_h.val(c.h);
    },
    getInitialSelect: function () {
      // if fields have values - use them as initial
      if (field_x && field_x.length && field_x.val() != '') {
        return [field_x.val(), field_y.val(), field_x.val() + field_w.val(), field_y.val() + field_h.val()];
      }

      // else use maximum possible
      var w = Math.min($image.width(), $image.height());
      var x = ($image.width() - w) / 2;
      var x2 = x + w;
      var y = ($image.height() - w) / 2
      var y2 = y + w;

      return [x, y, x2, y2];
    },
    submit_remote: function () {
      var $file = $(this);
      var $form = $('<form/>').attr({enctype: $h.attr('enctype')}).append($file);
      var $status = $('<div/>').addClass('progress').hide().after($file).slideDown();
      var $bar = $('<div/>').addClass('bar').appendTo($status);
      $form.ajaxSubmit({
        url: $h.attr('data-source'),
        type: 'post',
        uploadProgress: function (event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          $bar.width(percentVal)
        },
        statusCode: {
          200: function (r) {
            $h.html($(r).html());
            $obj.init();
          }
        }
      })
    }
  };

  $obj.init();

  return this;
}

$(function () {
  $('form.word-form').word_form()
});