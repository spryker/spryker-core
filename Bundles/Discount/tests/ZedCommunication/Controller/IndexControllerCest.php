<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedCommunication\Controller;

use Discount\ZedCommunicationTester;

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
     * @param \Discount\ZedCommunicationTester $i
     *
     * @return void
     */
    public function testICanOpenDiscountPage(ZedCommunicationTester $i)
    {
        $i->amOnPage('/discount/index/list');
        $i->seeResponseCodeIs(200);
    }

    /**
     * @param \Discount\ZedCommunicationTester $i
     *
     * @return void
     */
    public function testICanGoFromOverviewPageToCreatePage(ZedCommunicationTester $i)
    {
        $i->amOnPage('/discount/index/list');
        $i->click('Create new Discount');
        $i->seeResponseCodeIs(200);
        $i->canSeeCurrentUrlEquals('/discount/index/create');
    }

}
