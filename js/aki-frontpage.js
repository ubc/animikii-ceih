(function ($) {

  // init Masonry
  var $grid = $('.section-sub-grid').masonry({
    itemSelector: '.sub-grid-item',
    gutter: '.gutter-sizer',
    columnWidth: '.grid-sizer',
    percentPosition: true
  });
  // layout Masonry after each image loads
  $grid.imagesLoaded().progress( function() {
    $grid.masonry('layout');
  });

  $('.sub-section').on('shown', function () {
    $grid.masonry('layout');
  });

  $('.sub-section').on('show', function () {
    $(this).parent().addClass('sub-shown');
    $grid.masonry('layout');
  });

  $('.sub-section').on('hide', function () {
    $(this).parent().removeClass('sub-shown');
    $grid.masonry('layout');
  });

  $('.sub-section').on('hidden', function () {
    $grid.masonry('layout');
  });

}(jQuery));
