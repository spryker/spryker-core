<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetGui\Communication\Controller;

use SprykerTest\Zed\ProductSetGui\ProductSetGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSetGui
 * @group Communication
 * @group Controller
 * @group CreateControllerCest
 * Add your own group annotations below this line
 */
class CreateControllerCest
{
    /**
     * @param \SprykerTest\Zed\ProductSetGui\ProductSetGuiCommunicationTester $i
     *
     * @return void
     */
    public function testPriceColumnIsMissingInProductTableIfDynamicStoreIsEnabled(ProductSetGuiCommunicationTester $i): void
    {
        if ($i->isDynamicStoreEnabled() !== true) {
            $i->markTestSkipped();
        }

        $i->amOnPage($i::PRODUCT_SET_GUI_CREATE_URL);
        $i->seeResponseCodeIs(200);
        $i->switchToAssignProductsTab();
        $i->see($i::PRODUCTS_TO_ASSIGN_TAB_NAME);
        $i->dontSee($i::PRICE_COLUMN_NAME, $i::SELECTED_PRODUCTS_TABLE_SELECTOR);
    }
}
