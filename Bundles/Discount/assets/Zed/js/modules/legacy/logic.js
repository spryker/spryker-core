/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var discount = require('./helpers');

$(function(){

    $('#add-collector-container').click(function(e){
        e.preventDefault();
        discount.loadCollectorPluginForm($(this), 'cart_rule');
    });

    $('#add-collector-pool-container').click(function(e){
        e.preventDefault();
        discount.loadCollectorPluginForm($(this), 'voucher_codes');
    });

    $('#add-rules-container').click(function(e){
        e.preventDefault();
        discount.loadDecisionRuleForm($(this), 'cart_rule');
    });

    $('#add-rules-pool-container').click(function(e){
        e.preventDefault();
        discount.loadDecisionRuleForm($(this), 'voucher_codes');
    });

    $('.table-data-codes').DataTable({
        bRegex: false,
        bSmart: false,
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo($(column.footer()).empty())
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search(val ? val : '', false, false)
                            .draw();
                    } );

                column.data().unique().sort().each( function ( value, index ) {
                    select.append('<option value="' + value + '">' + value + '</option>' )
                } );
            } );
        }
    });

    $('.ibox-content').on('click', '.remove-form-collection', function(){
        $(this).closest('.col-md-6').remove();
    });

});
