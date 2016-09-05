<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Refund\Refund\Zed;

use Acceptance\Refund\Refund\Zed\Tester\RefundListTester;

/**
 * @group Acceptance
 * @group Refund
 * @group Refund
 * @group Zed
 * @group RefundListCest
 */
class RefundListCest
{

    /**
     * @param \Acceptance\Refund\Refund\Zed\Tester\RefundListTester $i
     *
     * @return void
     */
    public function testThatRefundListIsVisible(RefundListTester $i)
    {
        $i->canOpenRefundListPage();
    }

}
