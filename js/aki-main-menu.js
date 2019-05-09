(function ($) {

  var $mainMenuContainer = $('#main-menu-container'),
    $mainMenu = $('.main-menu'),
    $content = $('#body-container'),
    $window = $(window);

  $('#main-side-menu-toggle, #main-menu-close').click(function (e) {
    e.preventDefault();

    if ($content.hasClass('show-menu')) {
      $content.removeClass('show-menu');
    } else {
      $content.addClass('show-menu');
    }
  });

  checkMainMenuHeight();

  $window.resize(function (e) {
    checkMainMenuHeight();
  });

  function checkMainMenuHeight() {
    if ($window.height() < 700)
      $mainMenu.addClass('scroll');
    else
      $mainMenu.removeClass('scroll');
  }

}(jQuery));
