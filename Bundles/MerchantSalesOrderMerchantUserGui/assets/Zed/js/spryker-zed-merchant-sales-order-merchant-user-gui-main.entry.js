/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

'use strict';

var init = require('./modules/main');

$(document).ready(function() {
    init(
        '#merchant_shipment_group_form_shipment_shippingAddress_idCustomerAddress',
        '#merchant_shipment_group_form_shipment_shippingAddress',
        '#merchant_shipment_group_form_shipment_requestedDeliveryDate'
    );
});
