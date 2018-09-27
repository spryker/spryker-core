<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\PageObject;

use SprykerTest\Zed\Sales\PageObject\SalesDetailPage as BaseSalesDetailPage;

class SalesDetailPage extends BaseSalesDetailPage
{
    public const SELECTOR_REFUND_ROW = '//table[@data-qa="refund-list"]/tbody/tr[@data-qa="refund-row"]';
    public const REFUND_TOTAL_AMOUNT_SELECTOR = self::SELECTOR_REFUND_ROW . '/td[@data-qa="refund-amount-raw"]';

    public const BUTTON_REFUND = 'refund';
    public const STATE_RETURNED = 'returned';
    public const STATE_REFUNDED = 'refunded';

    public const ATTRIBUTE_REFUND_TOTAL_RAW = 'data-qa-raw';
}
