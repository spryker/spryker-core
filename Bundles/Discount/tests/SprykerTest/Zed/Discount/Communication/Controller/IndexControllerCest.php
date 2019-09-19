<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Controller;

use SprykerTest\Zed\Discount\DiscountCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Communication
 * @group Controller
 * @group IndexControllerCest
 * Add your own group annotations below this line
 */
class IndexControllerCest
{
    /**
     * @param \SprykerTest\Zed\Discount\DiscountCommunicationTester $i
     *
     * @return void
     */
    public function testICanOpenDiscountPage(DiscountCommunicationTester $i)
    {
        $i->amOnPage('/discount/index/list');
        $i->seeResponseCodeIs(200);
    }

    /**
     * @param \SprykerTest\Zed\Discount\DiscountCommunicationTester $i
     *
     * @return void
     */
    public function testICanGoFromOverviewPageToCreatePage(DiscountCommunicationTester $i)
    {
        $i->registerStoreRelationToggleFormTypePlugin();

        $i->amOnPage('/discount/index/list');
        $i->click('Create new Discount');
        $i->seeResponseCodeIs(200);
        $i->canSeeCurrentUrlEquals('/discount/index/create');
    }
}
