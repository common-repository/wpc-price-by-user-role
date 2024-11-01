(function($) {
  'use strict';

  $(function() {
    // ready
    init_terms();
    init_sortable();
    init_default_prices();
    init_hide_price();
  });

  $(document).on('change', '.wpcpu_apply, .wpcpu_apply_val', function() {
    init_apply_label($(this).closest('.wpcpu-item'));
  });

  $(document).on('change', '.wpcpu_apply', function() {
    init_terms();
  });

  $(document).on('change', '.wpcpu_apply_val', function() {
    var apply = $(this).closest('.wpcpu-item').find('.wpcpu_apply').val();

    $(this).data(apply, $(this).val().join());
  });

  $(document).
      on('change', '#_regular_price, #_sale_price,#product-type', function() {
        init_default_prices();
      });

  $(document).on('change', '.wpcpu-hide-price', function() {
    init_hide_price();
  });

  $(document).on('click touch', '.wpcpu-item-header', function(e) {
    if (($(e.target).closest('.wpcpu-item-duplicate').length === 0) &&
        ($(e.target).closest('.wpcpu-item-remove').length === 0)) {
      $(this).closest('.wpcpu-item').toggleClass('active');
    }
  });

  $(document).on('click touch', '.wpcpu-item-remove', function() {
    var r = confirm(
        'Do you want to remove this role? This action cannot undo.');

    if (r == true) {
      $(this).closest('.wpcpu-item').remove();
    }
  });

  $(document).on('click', '.wpcpu-item-new', function() {
    let $this = $(this), role = $('#wpcpu-item-new-role').val();

    $this.prop('disabled', true);
    $('.wpcpu-items').addClass('wpcpu-items-loading');

    $.post(ajaxurl, {
      action: 'wpcpu_add_role_price', role: role,
    }, function(response) {
      $('.wpcpu-items-wrapper .wpcpu-roles').append(response);
      $this.prop('disabled', false);
      $('.wpcpu-items').removeClass('wpcpu-items-loading');
      init_terms();
      init_hide_price();
    });
  });

  $(document).on('click', '.wpcpu-item-duplicate', function() {
    let $this = $(this), $item = $this.closest('.wpcpu-item'),
        role = $item.find('.wpcpu_role').val(),
        apply = $item.find('.wpcpu_apply').val(),
        apply_val = $item.find('.wpcpu_apply_val').val(),
        hide_price = $item.find('.wpcpu_hide_price').prop('checked') ?
            '1' :
            '0',
        price_text = $item.find('.wpcpu_price_text').val(),
        regular = $item.find('.wpcpu_regular').val(),
        sale = $item.find('.wpcpu_sale').val();

    $('.wpcpu-items').addClass('wpcpu-items-loading');

    $.post(ajaxurl, {
      action: 'wpcpu_add_role_price',
      role: role,
      apply: apply,
      apply_val: apply_val,
      hide_price: hide_price,
      price_text: price_text,
      regular: regular,
      sale: sale,
    }, function(response) {
      $(response).insertAfter($item);
      $('.wpcpu-items').removeClass('wpcpu-items-loading');
      init_terms();
      init_hide_price();
    });
  });

  $(document).on('change', '#wpcpu-select-enable', function() {
    init_single($(this).val());
  });

  $(document).on('click touch', '.wpcpu_overview', function(e) {
    var pid = $(this).attr('data-pid');
    var name = $(this).attr('data-name');
    var type = $(this).attr('data-type');

    if (!$('#wpcpu_overview_popup').length) {
      $('body').append('<div id=\'wpcpu_overview_popup\'></div>');
    }

    $('#wpcpu_overview_popup').html('Loading...');
    $('#wpcpu_overview_popup').dialog({
      minWidth: 460,
      title: '#' + pid + ' - ' + name,
      modal: true,
      dialogClass: 'wpc-dialog',
      open: function() {
        $('.ui-widget-overlay').bind('click', function() {
          $('#wpcpu_overview_popup').dialog('close');
        });
      },
    });

    var data = {
      action: 'wpcpu_overview', pid: pid, type: type,
    };

    $.post(ajaxurl, data, function(response) {
      $('#wpcpu_overview_popup').html(response);
    });

    e.preventDefault();
  });

  function init_sortable() {
    $('.wpcpu-roles').sortable({
      handle: '.wpcpu-item-move',
    });
  }

  function init_single(state) {
    if (state === 'global' || state === 'disable') {
      $('#wpcpu_settings .wpcpu-single-product').hide();
    } else {
      $('#wpcpu_settings .wpcpu-single-product').show();
    }
  }

  function init_apply_label($item) {
    let apply = $item.find('.wpcpu_apply').val(),
        apply_val = $item.find('.wpcpu_apply_val').val().join(),
        apply_label = '';

    if (apply === 'all') {
      apply_label = 'all';
    } else {
      apply_label = apply + ': ' + apply_val;
    }

    $item.find('.wpcpu-item-name-apply').html(apply_label);
  }

  function init_default_prices() {
    if ($('.wpcpu-default-prices').length) {
      if ($('#product-type').val() === 'simple') {
        $('.wpcpu-default-prices').show();
        $('.wpcpu-default-regular-price strong').
            html($('#_regular_price').val());
        $('.wpcpu-default-sale-price strong').html($('#_sale_price').val());
      } else {
        $('.wpcpu-default-prices').hide();
      }
    }
  }

  function init_hide_price() {
    $('.wpcpu-hide-price').each(function() {
      if ($(this).is(':checked')) {
        $(this).closest('.wpcpu-item').find('.show-if-hide-price').show();
        $(this).closest('.wpcpu-item').find('.hide-if-hide-price').hide();
      } else {
        $(this).closest('.wpcpu-item').find('.show-if-hide-price').hide();
        $(this).closest('.wpcpu-item').find('.hide-if-hide-price').show();
      }
    });
  }

  function init_terms() {
    $('.wpcpu_terms').each(function() {
      var $this = $(this);
      var apply = $this.closest('.wpcpu-item').find('.wpcpu_apply').val();

      if (apply === 'all') {
        $this.closest('.wpcpu-item').find('.hide_if_apply_all').hide();
      } else {
        $this.closest('.wpcpu-item').find('.hide_if_apply_all').show();
      }

      $this.selectWoo({
        ajax: {
          url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
            return {
              q: params.term, action: 'wpcpu_search_term', taxonomy: apply,
            };
          }, processResults: function(data) {
            var options = [];

            if (data) {
              $.each(data, function(index, text) {
                options.push({id: text[0], text: text[1]});
              });
            }
            return {
              results: options,
            };
          }, cache: true,
        }, minimumInputLength: 1,
      });

      if ($this.data(apply) !== undefined && $this.data(apply) !== '') {
        $this.val(String($this.data(apply)).split(',')).change();
      } else {
        $this.val([]).change();
      }
    });
  }
})(jQuery);
