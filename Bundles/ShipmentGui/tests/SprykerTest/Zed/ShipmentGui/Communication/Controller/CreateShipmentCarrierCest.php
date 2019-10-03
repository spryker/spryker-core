<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentGui\Communication\Controller;

use SprykerTest\Zed\ShipmentGui\PageObject\CreateShipmentCarrierPage;
use SprykerTest\Zed\ShipmentGui\ShipmentGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentGui
 * @group Communication
 * @group Controller
 * @group CreateShipmentCarrierCest
 * Add your own group annotations below this line
 */
class CreateShipmentCarrierCest
{
    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ShipmentGuiCommunicationTester $i): void
    {
        $i->amOnPage(CreateShipmentCarrierPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Administration/ Shipments / Create new Shipment Carrier');
    }
}
