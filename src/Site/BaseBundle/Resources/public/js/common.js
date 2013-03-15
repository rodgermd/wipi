$.fn.assign_tooltips = function () {
  var $h = this;
  $('.has-tooltip', $h).tooltip();
  return this;
};

$(function () {
  $('#content-wrapper').assign_tooltips();
});