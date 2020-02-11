/**
 * Copyright (c) 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

'use strict';

var init = require('./modules/main');

$(document).ready(function() {
    init(
        '#shipment_group_form_shipment_shippingAddress_idCustomerAddress',
        '#shipment_group_form_shipment_shippingAddress',
        '#shipment_group_form_shipment_requestedDeliveryDate'
    );
});
