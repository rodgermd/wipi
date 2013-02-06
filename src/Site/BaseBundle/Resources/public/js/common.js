$.fn.assign_tooltips = function () {
  var $h = this;
  $('.has-tooltip', $h).each(function () {
    var $e = $(this);
    var $o = { placement:top };
    if ($e.is('.tooltip-at-left')) {
      $o.placement = 'left';
    }
    else if ($e.is('.tooltip-at-right')) {
      $o.placement = 'right';
    }
    else if ($e.is('.tooltip-at-bottom')) {
      $o.placement = 'bottom';
    }
    $e.tooltip($o);
  });
  return this;
};

$(function () {
  $('#content-wrapper').assign_tooltips();
});