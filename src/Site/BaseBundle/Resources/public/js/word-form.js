$.fn.word_form = function() {
  var $f = this;
  var $obj;

  $obj = {
    init: function() {
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
    init:function () {
      searcher.on('click', $obj.search);
      translations_container.delegate('.translated-word', 'click', $obj.use_translation);
    },
    search:function () {
      translations_container.html('');
      translated_word.val('');

      if (!source_word.val().length) return false;
      $.ajax({
        url:searcher.attr('href'),
        type:'post',
        dataType:'json',
        data:{ word:source_word.val() },
        beforeStart:function () {
          searcher.button('loading');
          translations_container.addClass(loading_class);
        },
        success:$obj.render_translations,
        onComplete:function () {
          searcher.button('reset');
          translations_container.removeClass(loading_class)
        }
      });
    },
    render_translations:function (r) {
      // no results - return
      if (!Object.keys(r).length) return;
      // single result - no need to render choices
      if (Object.keys(r).length == 1) {
        translated_word.val(Object.keys(r)[0]);
        return;
      }
      var $list = $('<ul/>'),
          row = $('<li class="word"/>'),
          translator = $('<span class="translator-service"/>'),
          word_container = $('<span class="translated-word"/>');
      for (var word in r) {
        var $row = row.clone();
        word_container.clone().text(word).appendTo($row);
        for (var key in r[word]) {
          translator.clone().addClass('service-' + r[word][key]).appendTo($row);
        }
        $list.append($row);
      }
      $list.appendTo(translations_container);
    },
    use_translation: function() {
      translated_word.val($(this).text());
    }
  };

  $obj.init();

  return this;
};

$.fn.word_form_image = function() {
  var $h = this;
  var $obj;

  $obj = {
    init: function() {
      $(':file', $h).on('change', $obj.submit_remote);
      console.log($('.crop-container img', $h));
      $('.crop-container img', $h).Jcrop({aspectRatio: 1});
    },
    submit_remote: function() {
      var $file = $(this);
      var $form = $('<form/>').attr({enctype : $h.attr('enctype')}).append($file);
      var $status = $('<div/>').addClass('progress').hide().after($file).slideDown();
      var $bar = $('<div/>').addClass('bar').appendTo($status);
      $form.ajaxSubmit({
        url: $h.attr('data-source'),
        type: 'post',
        uploadProgress: function(event, position, total, percentComplete) {
          var percentVal = percentComplete + '%';
          $bar.width(percentVal)
        },
        statusCode : {
          200: function(r) {
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