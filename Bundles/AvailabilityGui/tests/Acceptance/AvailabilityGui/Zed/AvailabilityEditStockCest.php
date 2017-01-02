<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\AvailabilityGui\Zed;

use Acceptance\AvailabilityGui\Zed\PageObject\AvailabilityPage;
use Acceptance\AvailabilityGui\Zed\Tester\AvailabilityTester;

/**
 * @group Acceptance
 * @group AvailabilityGui
 * @group Zed
 * @group AvailabilityEditStockCest
 */
class AvailabilityEditStockCest
{

    /**
     * @param \Acceptance\AvailabilityGui\Zed\Tester\AvailabilityTester $i
     *
     * @return void
     */
    public function testEditExistinStock(AvailabilityTester $i)
    {
        $i->wantTo('Edit avalability stock');
        $i->expect('New stock added.');

        $i->amOnPage(
            sprintf(
                AvailabilityPage::AVAILABILITY_EDIT_STOCK_URL,
                AvailabilityPage::AVAILABILITY_ID,
                AvailabilityPage::AVAILABILITY_SKU,
                AvailabilityPage::AVAILABILITY_ABSTRACT_PRODUCT_ID
            )
        );

        $i->see(AvailabilityPage::PAGE_AVAILABILITY_EDIT_HEADER);

        $i->fillField('//*[@id="AvailabilityGui_stock_stocks_0_quantity"]', 50);
        $i->click('input[type=submit]');
        $i->see(AvailabilityPage::SUCCESS_MESSAGE);

        $i->fillField('//*[@id="AvailabilityGui_stock_stocks_0_quantity"]', 'string');
        $i->click('input[type=submit]');
        $i->see('This value is not valid.');

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityPage::PAGE_AVAILABILITY_VIEW_HEADER);
    }

}
