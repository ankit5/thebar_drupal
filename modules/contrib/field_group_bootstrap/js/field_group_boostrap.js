/**
 * @file
 */

(($, Drupal, cookies) => {
  Drupal.behaviors.field_group_bootstrap = {
    attach: (context, settings) => {
      function memoryClick(type = '', currentTab, currentGroup) {
        // Retrieve a cookie.
        let cookiesType = cookies.get(type);
        if (cookiesType) {
          cookiesType = JSON.parse(cookiesType);
        } else {
          cookiesType = {};
        }
        cookiesType[currentGroup] = currentTab;
        // Set a cookie.
        cookies.set(type, JSON.stringify(cookiesType));
      }

      // Active first tab.
      var tabClick = false;
      $('.field-group-bootstrap_tabs-wrapper', context).once("field-group-bootstrap_tabs-wrapper").each(function () {
        if ($('.field-group-bootstrap_tabs-wrapper').length && !tabClick) {
          let bootstrap_tabs = cookies.get('bootstrap_tabs');
          if (bootstrap_tabs) {
            tabClick = false;
            let cookiesType = JSON.parse(bootstrap_tabs);
            $.each(cookiesType, function (key, value) {
              let selectorTab = '.field-group-bootstrap_tabs-wrapper .nav-link[aria-controls="' + value + '"]';
              if ($(selectorTab).length) {
                $(selectorTab).click();
                tabClick = true;
                return false;
              }
            });
            if (!tabClick) {
              $('.field-group-bootstrap_tabs-wrapper .nav-link').first().trigger('click');
            }
          } else {
            $('.field-group-bootstrap_tabs-wrapper .nav-link').first().trigger('click');
          }
        }
      });

      // Memory when click on tab.
      $('.field-group-bootstrap_tabs-wrapper .nav .nav-link', context).once().click(function () {
        memoryClick('bootstrap_tabs', $(this).attr('aria-controls'), $(this).data('group'));
      });

      // Memory when click on list item scrollby.
      $('.field-group-bootstrap_scrollby-wrapper .list-group .list-group-item', context).once().click(function () {
        memoryClick('bootstrap_scrollby', $(this).data('controls'), $(this).data('group'));
      });

      // Memory when click on accordion.
      $('.field-group-bootstrap_accordion-wrapper .accordion-button', context).once().click(function () {
        memoryClick('bootstrap_accordion', $(this).data('controls'), $(this).data('group'));
      });
    },
  };
})(jQuery, Drupal, window.Cookies);
