(function($, Drupal) {

 //Popup in function so we can pass content title to the popup

 function Confirm_popup(title = "") {

   var content = '<div>Are you sure you want save <b>' + title + '</b>?</div>';

   confirmationDialog = Drupal.dialog(content, {

     dialogClass: 'confirm-dialog',

     resizable: true,

     closeOnEscape: false,

     width: 600,

     title: "Saving Confirmation",

     buttons: [

       {

         text: 'Confirm',

         class: 'button--primary button',

         click: function() {

           $('#custom-form-submit-after-check').click();

         }

       },

       {

         text: 'Close',

         click: function() {

           $(this).dialog('close');

         }

       }

     ],

   });

   confirmationDialog.showModal();

 }

 

 // call to function to open popup

 $("#custom-form-submit-after-check").click(function(e) {

   e.preventDefault();

   if ($('#edit-title-0-value').val() != '') {

     title = $('#edit-title-0-value').val();

   }

   Confirm_popup(title);

 });

 

})(jQuery, Drupal);

