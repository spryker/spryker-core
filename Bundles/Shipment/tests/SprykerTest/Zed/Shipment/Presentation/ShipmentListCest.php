<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Presentation;

use SprykerTest\Zed\Shipment\PageObject\ShipmentCarrierAddPage;
use SprykerTest\Zed\Shipment\PageObject\ShipmentListPage;
use SprykerTest\Zed\Shipment\PageObject\ShipmentMethodAddPage;
use SprykerTest\Zed\Shipment\ShipmentPresentationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Presentation
 * @group ShipmentListCest
 * Add your own group annotations below this line
 */
class ShipmentListCest
{
    /**
     * @param \SprykerTest\Zed\Shipment\ShipmentPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenShipmentList(ShipmentPresentationTester $i)
    {
        $i->amOnPage(ShipmentListPage::URL);
        $i->seeElement(['class' => ShipmentListPage::SELECTOR_TABLE]);
    }

    /**
     * @param \SprykerTest\Zed\Shipment\ShipmentPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenAddShipmentMethodPage(ShipmentPresentationTester $i)
    {
        $i->amOnPage(ShipmentListPage::URL);
        $i->click(ShipmentListPage::BUTTON_ADD_METHOD);
        $i->seeCurrentUrlEquals(ShipmentMethodAddPage::URL);
    }

    /**
     * @param \SprykerTest\Zed\Shipment\ShipmentPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenAddShipmentCarrierPage(ShipmentPresentationTester $i)
    {
        $i->amOnPage(ShipmentListPage::URL);
        $i->click(ShipmentListPage::BUTTON_ADD_CARRIER);
        $i->seeCurrentUrlEquals(ShipmentCarrierAddPage::URL);
    }
}
