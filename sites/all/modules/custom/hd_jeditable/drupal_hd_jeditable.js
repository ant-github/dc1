(function ($) {
  Drupal.behaviors.hd_jeditable = {
    attach: function(context) {
      $('.hd-jeditable-textfield', context).editable('/hd_jeditable/ajax/save', {
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
        cancel    : 'Cancel',
        submit    : 'Save',
        onblur    : 'submit',
        style     : 'display: inherit; min-width: 100px;'
      });
      $('.hd-jeditable-textarea', context).editable('/hd_jeditable/ajax/save', {
        type      : 'textarea',
        cancel    : 'Cancel',
        submit    : 'OK',
        onblur    : 'submit',
        indicator : 'Saving...',
        tooltip   : 'Click to edit...'
      });
      $('.hd-jeditable-select', context).editable('/hd_jeditable/ajax/save', {
        loadurl  : '/hd_jeditable/ajax/load',
        type     : 'select',
        submit   : 'OK',
        onblur   : 'submit',
        style    : 'display: inherit'
      });
    }
  };
})(jQuery);
