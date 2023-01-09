/**
 * @file
 */

(($, Drupal, once) => {
  Drupal.behaviors.field_group_multistep = {
    attach: (context, settings) => {
      function showStep(multiStep, step) {
        multiStep.find('.step').removeClass('d-block').addClass('d-none');
        let stepCount = multiStep.find('.step').length - 1;
        let progress = (100 / stepCount) * step;
        if (multiStep.find('.step[data-step="' + step + '"]').length) {
          multiStep.find('.step[data-step="' + step + '"]').removeClass('d-none').addClass('d-block');
        }
        multiStep.find('.progress-bar').css('width', progress + '%');
      }

      var current_step = 0;
      $('.btn-next').once().on('click', function () {
        current_step++;
        let multiStep = $(this).closest('.multistep');
        let totalStep = multiStep.find('.step').length - 1;
        showStep(multiStep, current_step);
        multiStep.find('.btn-prev').show();
        if (totalStep == current_step) {
          $(this).hide();
        }
      });
      $('.btn-prev').once().on('click', function () {
        current_step--;
        let multiStep = $(this).closest('.multistep');
        showStep(multiStep, current_step);
        multiStep.find('.btn-next').show();
        if (!current_step) {
          $(this).hide();
        }
      });
    }
  };
})(jQuery, Drupal, once);
