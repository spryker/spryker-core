/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

require('ZedGui');
require('../../sass/main.scss');

$(document).ready(function(){
    $('#create-discount-button').on('click', function() {
        $('#discount-form').submit();
    });

    $('.tabs-manager .btn-tab-previous').on('click', function(){
        $(this).
            closest('.tabs-manager').
            children('.nav').
            children('.active').
            prev('li').
            find('a').
            trigger('click');
    });

    $('.tabs-manager .btn-tab-next').on('click', function(){
        $(this).
            closest('.tabs-manager').
            children('.nav').
            children('.active').
            next('li').
            find('a').
            trigger('click');
    });

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
