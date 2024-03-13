function OtenerId(order_id) {
    jQuery.ajax({
        type: "post",
        url: ajax_object.url,
        data: {
            'action': 'see_product',
            'num_order': order_id
        },
        success: function (data) {
            data = data.slice(0, -1)
            var datos = JSON.parse(data)

            _.each(datos, function (dat) {
                console.log("esta es : ", dat.product_id)
                var htmlTags = '<tr class="remove-for">' +
                    '<th>' + dat.product_id + '</th>' +
                    '<td>' + dat.product_qty + '</td>' +
                    '<td>' + dat.product_net_revenue + '</td>' +
                    '</tr>';
                jQuery('#table_product tbody').append(htmlTags);
                // jQuery('#probajaja').text("hola bb : " + dat.product_id)
            });
            // $tr = jQuery(this).closest('tr')

            jQuery('#exampleModal').modal('show');
        }
    });
}

jQuery(document).ready(function () {
    jQuery("#exampleModal").on('hidden.bs.modal', function () {
        jQuery('.remove-for').remove()
    });

    jQuery('.switch-button__checkbox').change(function () {
        console.log("sd");
        jQuery('#cargaModal').modal('show');
        var total = 10;
        if (jQuery(this).prop('checked') == true) {
            jQuery.ajax({
                type: "post",
                url: ajax_object.url,
                data: {
                    'action': 'swicht_state',
                    'state': 1,
                    'id_vcard': jQuery(this).attr('name')
                },
                success: function (data) {
                },
                complete: function (xhr, status) {
                    jQuery('#cargaModal').modal('hide');
                }
            });
        }
        else {
            jQuery.ajax({
                type: "post",
                url: ajax_object.url,
                data: {
                    'action': 'swicht_state',
                    'state': 0,
                    'id_vcard': jQuery(this).attr('name')
                },
                success: function (data) {

                },
                complete: function (xhr, status) {
                    jQuery('#cargaModal').modal('hide');
                }
            });
            total = total - 5;
        }
    });
});

// bind a button or a link to open the dialog
function editaData(datos) {
    data = datos.split('||')
    jQuery('#idcard').text(data[0])
    jQuery('#iduser').val(data[1])
}

function updataData() {
    idvcard = jQuery('#idcard').text()
    iduser = jQuery('#iduser').val()
    jQuery.ajax({
        type: "post",
        url: ajax_object.url,
        data: {
            'action': 'update_user',
            'user_id': iduser,
            'id_vcard': idvcard
        },
        success: function (data) {
        },
        complete: function (xhr, status) {
            jQuery('#exampleModal').modal('hide');
            location.reload();
        }
    });
}