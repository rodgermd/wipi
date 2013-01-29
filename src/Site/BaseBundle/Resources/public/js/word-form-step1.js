$.fn.word_form_step1 = function(){
  var $f = this;
  var $obj;

  $obj = {
    init: function() {
      console.log('aaa');
    }
  };

  $obj.init();

  return this;
};

$(function(){ $('form.word-form-step1').word_form_step1() });