<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Refund\Refund\Zed\Tester;

use Acceptance\Refund\Refund\Zed\PageObject\RefundListPage;
use Refund\AcceptanceTester;

class RefundListTester extends AcceptanceTester
{

    public function canOpenRefundListPage()
    {
        $i = $this;
        $i->amOnPage(RefundListPage::URL);
    }

}
