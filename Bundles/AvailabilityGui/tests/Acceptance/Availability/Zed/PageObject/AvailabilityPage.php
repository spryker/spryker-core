<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Acceptance\AvailabilityGui\Zed\PageObject;

class AvailabilityGuiPage
{

    const AvailabilityGui_ID = 107;
    const AvailabilityGui_SKU = '828188-1';
    const AvailabilityGui_ABSTRACT_PRODUCT_ID = 107;

    const AvailabilityGui_LIST_URL = '/AvailabilityGui';
    const AvailabilityGui_VIEW_URL = '/AvailabilityGui/index/view?id-product=%d';
    const AvailabilityGui_EDIT_STOCK_URL = 'AvailabilityGui/index/edit?id-product=%d&sku=%s&id-abstract=%d';

    const SUCCESS_MESSAGE = 'Stock successfully updated';

    const PAGE_AvailabilityGui_VIEW_HEADER = 'Product AvailabilityGui';
    const PAGE_AvailabilityGui_LIST_HEADER = 'AvailabilityGui list';
    const PAGE_AvailabilityGui_EDIT_HEADER = 'Edit Stock';

}
