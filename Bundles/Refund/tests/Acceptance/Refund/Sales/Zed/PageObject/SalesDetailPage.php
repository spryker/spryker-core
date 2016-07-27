<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Refund\Sales\Zed\PageObject;

use Acceptance\Sales\Order\Zed\PageObject\SalesDetailPage as BaseSalesDetailPage;

class SalesDetailPage extends BaseSalesDetailPage
{

    const REFUND_ROW_SELECTOR = '//table[@data-qa="refund-list"]/tbody/tr[@data-qa="refund-row"]';
    const REFUND_TOTAL_AMOUNT_SELECTOR = self::REFUND_ROW_SELECTOR . '/td[@data-qa="refund-amount-raw"]';

    const ATTRIBUTE_REFUND_TOTAL_RAW = 'data-qa-raw';
}
