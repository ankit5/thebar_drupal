
/* function preventBack() { window.history.forward(); }  
    setTimeout("preventBack()", 0);  
    window.onunload = function () { null };*/
  


(function($, Drupal, drupalSettings) {

   
$(".flatpickr").flatpickr({
  inline: true,
  enableTime: true,
  noCalendar: true,
  time_24hr: true
});



})(jQuery, Drupal, drupalSettings);;