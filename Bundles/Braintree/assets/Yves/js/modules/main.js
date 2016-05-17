/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved. 
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file. 
 */

'use strict';

var $ = require('jquery');
var paymentMethod = require('./payment-method');

$(document).ready(function() {
    paymentMethod.init({
        formSelector: '#payment-form',
        currentPaymentMethodSelector: '#paymentForm_paymentSelection input:checked',
        nonceInputName: 'payment_method_nonce'
    });
});
