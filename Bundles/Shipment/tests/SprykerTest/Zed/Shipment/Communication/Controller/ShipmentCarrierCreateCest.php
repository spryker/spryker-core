<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Communication\Controller;

use SprykerTest\Zed\Shipment\PageObject\ShipmentCarrierAddPage;
use SprykerTest\Zed\Shipment\ShipmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Communication
 * @group Controller
 * @group ShipmentCarrierCreateCest
 * Add your own group annotations below this line
 */
class ShipmentCarrierCreateCest
{
    /**
     * @param \SprykerTest\Zed\Shipment\ShipmentCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ShipmentCommunicationTester $i)
    {
        $i->amOnPage(ShipmentCarrierAddPage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Shipment / Shipment Carrier / Create new Shipment Carrier');
    }
}
