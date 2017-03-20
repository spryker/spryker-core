<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Communication\Controller;

use Discount\CommunicationTester;

/**
 * @group Spryker
 * @group Zed
 * @group Communication
 * @group Controller
 * @group IndexControllerCest
 *
 * @group Functional
 */
class IndexControllerCest
{

    /**
     * @param \Discount\CommunicationTester $i
     *
     * @return void
     */
    public function testICanOpenDiscountPage(CommunicationTester $i)
    {
        $i->amOnPage('/discount/index/list');
    }

}
