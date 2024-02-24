import * as section from './section.js';
import * as blocks from './blocks.js';
import * as dropZones from './drop-zones.js';

(($, Drupal, once) => {

  Drupal.LBPlus = {};

  /**
   * LB+ ajax error.
   *
   * Removes the ajax spinner and displays an error message.
   */
  Drupal.LBPlus.ajaxError = (message, error) => {
    // Remove the AJAX progress spinner.
    document.querySelectorAll('.ajax-progress').forEach(progress => {
      progress.remove();
    });
    // Display a message.
    const messages = new Drupal.Message();
    messages.add('<span id="lb-plus-ajax-error">' + message + '</span>', {type: 'error'});
    document.getElementById('lb-plus-ajax-error').scrollIntoView({behavior: 'smooth', block: 'start'});
    console.error(error.responseText);
  };

  section.default($, Drupal, once, dropZones);
  blocks.default($, Drupal, once, dropZones);
})(jQuery, Drupal, once);
