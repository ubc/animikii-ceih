(function ($) {

  // init Masonry
  var $grid = $('.section-sub-grid').masonry({
    itemSelector: '.sub-grid-item',
    gutter: '.gutter-sizer',
    columnWidth: '.grid-sizer',
    percentPosition: true
  });

  $('.section-sub').imagefill();

  // layout Masonry after each image loads
  $grid.imagesLoaded().progress( function() {
    $grid.masonry('layout');
  });

  var observer = lozad(document.querySelectorAll('.section-header-image img, .section-sub imgs'));
  observer.observe();

}(jQuery));
