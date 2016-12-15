<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\AvailabilityGui\Zed;

use Acceptance\AvailabilityGui\Zed\PageObject\AvailabilityGuiPage;
use Acceptance\AvailabilityGui\Zed\Tester\AvailabilityGuiTester;

/**
 * @group Acceptance
 * @group AvailabilityGui
 * @group Zed
 * @group AvailabilityGuiEditStockCest
 */
class AvailabilityGuiEditStockCest
{

    /**
     * @param \Acceptance\AvailabilityGui\Zed\Tester\AvailabilityGuiTester $i
     *
     * @return void
     */
    public function testEditExistinStock(AvailabilityGuiTester $i)
    {
        $i->wantTo('Edit avalability stock');
        $i->expect('New stock added.');

        $i->amOnPage(
            sprintf(
                AvailabilityGuiPage::AvailabilityGui_EDIT_STOCK_URL,
                AvailabilityGuiPage::AvailabilityGui_ID,
                AvailabilityGuiPage::AvailabilityGui_SKU,
                AvailabilityGuiPage::AvailabilityGui_ABSTRACT_PRODUCT_ID
            )
        );

        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_EDIT_HEADER);

        $i->fillField('//*[@id="AvailabilityGui_stock_stocks_0_quantity"]', 50);
        $i->click('input[type=submit]');
        $i->see(AvailabilityGuiPage::SUCCESS_MESSAGE);

        $i->fillField('//*[@id="AvailabilityGui_stock_stocks_0_quantity"]', 'string');
        $i->click('input[type=submit]');
        $i->see('This value is not valid.');

        $i->click('//*[@id="page-wrapper"]/div[2]/div[2]/div/a');
        $i->see(AvailabilityGuiPage::PAGE_AvailabilityGui_VIEW_HEADER);
    }

}
