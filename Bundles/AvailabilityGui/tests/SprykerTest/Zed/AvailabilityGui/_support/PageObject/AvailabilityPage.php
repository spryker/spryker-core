<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityGui\PageObject;

class AvailabilityPage
{
    public const AVAILABILITY_ID = 107;
    public const AVAILABILITY_SKU = '828188-1';
    public const AVAILABILITY_ABSTRACT_PRODUCT_ID = 107;
    public const AVAILABILITY_ID_STORE = 1;

    public const AVAILABILITY_LIST_URL = '/availability-gui';
    public const AVAILABILITY_VIEW_URL = '/availability-gui/index/view?id-product=%d&id-store=%s';
    public const AVAILABILITY_EDIT_STOCK_URL = 'availability-gui/index/edit?id-product=%d&sku=%s&id-abstract=%d&id-store=%s';

    public const SUCCESS_MESSAGE = 'Stock successfully updated';

    public const PAGE_AVAILABILITY_VIEW_HEADER = 'Product availability';
    public const PAGE_AVAILABILITY_LIST_HEADER = 'Availability list';
    public const PAGE_AVAILABILITY_EDIT_HEADER = 'Edit Stock';
}
