<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Shipment\Zed;

use Acceptance\Shipment\Zed\PageObject\ShipmentCarrierAddPage;
use Acceptance\Shipment\Zed\PageObject\ShipmentListPage;
use Acceptance\Shipment\Zed\PageObject\ShipmentMethodAddPage;
use Acceptance\Shipment\Zed\Tester\ShipmentListTester;

/**
 * @group Acceptance
 * @group Shipment
 * @group Zed
 * @group ShipmentListCest
 */
class ShipmentListCest
{

    /**
     * @param \Acceptance\Shipment\Zed\Tester\ShipmentListTester $i
     *
     * @return void
     */
    public function testICanOpenShipmentList(ShipmentListTester $i)
    {
        $i->amOnPage(ShipmentListPage::URL);
        $i->seeElement(['class' => ShipmentListPage::SELECTOR_TABLE]);
    }

    /**
     * @param \Acceptance\Shipment\Zed\Tester\ShipmentListTester $i
     *
     * @return void
     */
    public function testICanOpenAddShipmentMethodPage(ShipmentListTester $i)
    {
        $i->amOnPage(ShipmentListPage::URL);
        $i->click(ShipmentListPage::BUTTON_ADD_METHOD);
        $i->seeCurrentUrlEquals(ShipmentMethodAddPage::URL);
    }

    /**
     * @param \Acceptance\Shipment\Zed\Tester\ShipmentListTester $i
     *
     * @return void
     */
    public function testICanOpenAddShipmentCarrierPage(ShipmentListTester $i)
    {
        $i->amOnPage(ShipmentListPage::URL);
        $i->click(ShipmentListPage::BUTTON_ADD_CARRIER);
        $i->seeCurrentUrlEquals(ShipmentCarrierAddPage::URL);
    }

}
