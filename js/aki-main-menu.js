(function ($) {

  var $mainMenuContainer = $('#main-menu-container'),
    $menuTopNav = $('#menu-top-nav'),
    $menuBottom = $('.menu-bottom'),
    $content = $('#body-container'),
    $window = $(window);

  var parents = $('.menu-container ul li.active')
    .parentsUntil('#menu-top-nav', 'li.menu-item')
    .addClass('open');

  $('#main-side-menu-toggle, #main-menu-close').click(function (e) {
    e.preventDefault();

    if ($content.hasClass('show-menu')) {
      $content.removeClass('show-menu');
    } else {
      $content.addClass('show-menu');
    }
  });

  $(".menu-item-has-children > a").on("click", function(n) {
    var isOpen = $(this).parent().hasClass("open");
    if(isOpen) {
      $(this).parent().removeClass("open");
    } else {
      n.preventDefault();
      var text = $(this).text();
      $(this).text('Continue to ' + text);
      $(this).parent().addClass("open");
    }
    setMenuHeight();
  });

  setMenuHeight();

  function setMenuHeight() {
    var cHeight = $mainMenuContainer.height();
    var mbHeight = $menuBottom.height();
    var mTHeight = $menuTopNav.height();

    var doScroll = (mbHeight + mTHeight) > cHeight;

    if(doScroll) {
      $mainMenuContainer.addClass('scroll');
    } else {
      $mainMenuContainer.removeClass('scroll');
    }
  }

  $window.resize(function (e) {
    setMenuHeight();
  });

}(jQuery));
