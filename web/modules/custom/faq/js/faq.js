(function ($, Drupal, drupalSettings) {


    $(document).on('click', function (event) {
      let target = $(event.target),
      navItem = target.parents('.nav-item'),
      isSelected = navItem.hasClass('active'),
        taxonomyTabs = $('.taxonomy-term-tab'),
      linkedTab = $('#'+navItem.data('relatedTab'));
      if (navItem.length) {
        $('.nav-item').removeClass('active');
        taxonomyTabs.hide();
        if(!isSelected) {
          navItem.addClass('active');
          linkedTab.show()
        }else{
          linkedTab.hide();
        }
      }
    })




})(jQuery, Drupal, drupalSettings);
