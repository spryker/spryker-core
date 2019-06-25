/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var init = require('./modules/main');

$(document).ready(function() {
    init(
        '#shipment_edit_form_id_customer_address',
        '#shipment_edit_form_shipping_address',
        '#shipment_edit_form_requested_delivery_date'
    );
});
