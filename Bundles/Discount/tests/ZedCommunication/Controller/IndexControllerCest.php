<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace ZedCommunication\Controller;

use Discount\ZedCommunicationTester;

/**
 * Auto-generated group annotations
 * @group Discount
 * @group ZedCommunication
 * @group Controller
 * @group IndexControllerCest
 * Add your own group annotations below this line
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
