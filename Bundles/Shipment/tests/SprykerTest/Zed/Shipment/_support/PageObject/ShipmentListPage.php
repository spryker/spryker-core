<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\PageObject;

class ShipmentListPage
{
    const URL = '/shipment';

    const SELECTOR_TABLE = 'dataTables_wrapper';

    const BUTTON_ADD_CARRIER = '//div[@class="title-action"]/a[1]';
    const BUTTON_ADD_METHOD = '//div[@class="title-action"]/a[2]';
}
