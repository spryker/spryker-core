<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentGui\Presentation;

use SprykerTest\Zed\ShipmentGui\PageObject\CreateShipmentCarrierPage;
use SprykerTest\Zed\ShipmentGui\PageObject\CreateShipmentMethodPage;
use SprykerTest\Zed\ShipmentGui\PageObject\ShipmentMethodPage;
use SprykerTest\Zed\ShipmentGui\ShipmentGuiPresentationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentGui
 * @group Presentation
 * @group ShipmentMethodListCest
 * Add your own group annotations below this line
 */
class ShipmentMethodListCest
{
    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenShipmentMethodList(ShipmentGuiPresentationTester $i): void
    {
        $i->amOnPage(ShipmentMethodPage::URL);
        $i->seeElement(['class' => ShipmentMethodPage::SELECTOR_TABLE]);
    }

    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenAddShipmentCarrierPage(ShipmentGuiPresentationTester $i): void
    {
        $i->amOnPage(ShipmentMethodPage::URL);
        $i->click(ShipmentMethodPage::BUTTON_ADD_CARRIER);
        $i->seeCurrentUrlEquals(CreateShipmentCarrierPage::URL);
    }

    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenAddShipmentMethodPage(ShipmentGuiPresentationTester $i): void
    {
        $i->amOnPage(ShipmentMethodPage::URL);
        $i->click(ShipmentMethodPage::BUTTON_ADD_METHOD);
        $i->seeCurrentUrlEquals(CreateShipmentMethodPage::URL);
    }
}
