<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityGui\Communication\Controller;

use Codeception\Example;
use SprykerTest\Zed\AvailabilityGui\AvailabilityGuiCommunicationTester;
use SprykerTest\Zed\AvailabilityGui\PageObject\AvailabilityPage;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityGui
 * @group Communication
 * @group Controller
 * @group AvailabilityEditStockCest
 * Add your own group annotations below this line
 */
class AvailabilityEditStockCest
{
    /**
     * @dataProvider stockProvider
     *
     * @param \SprykerTest\Zed\AvailabilityGui\AvailabilityGuiCommunicationTester $i
     * @param \Codeception\Example $example
     *
     * @return void
     */
    public function testEditExistingStock(AvailabilityGuiCommunicationTester $i, Example $example)
    {
        $productConcreteTransfer = $i->haveFullProduct();
        $i->haveAvailabilityAbstract($productConcreteTransfer);
        $i->wantTo('Edit availability stock');
        $i->expect('New stock added.');

        $i->amOnPage(
            sprintf(
                AvailabilityPage::AVAILABILITY_EDIT_STOCK_URL,
                $productConcreteTransfer->getIdProductConcrete(),
                $productConcreteTransfer->getSku(),
                $productConcreteTransfer->getFkProductAbstract(),
                AvailabilityPage::AVAILABILITY_ID_STORE
            )
        );

        $i->seeBreadcrumbNavigation('Dashboard / Products / Availability / Edit Stock');

        $i->see(AvailabilityPage::PAGE_AVAILABILITY_EDIT_HEADER);

        $i->fillField('//*[@id="AvailabilityGui_stock_stocks_0_quantity"]', $example['quantity']);
        $i->click('Save');
        $i->seeResponseCodeIs($example['expectedResponseCode']);

        $i->fillField('//*[@id="AvailabilityGui_stock_stocks_0_quantity"]', 'string');
        $i->click('input[type=submit]');
        $i->see('This value is not valid.');

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
    }

    /**
     * @return array
     */
    protected function stockProvider(): array
    {
        return [
            'int stock' => ['quantity' => '50', 'expectedResponseCode' => 200],
            'float stock' => ['quantity' => '50.88', 'expectedResponseCode' => 200],
        ];
    }
}
