<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment\Communication\Controller;

use SprykerTest\Zed\Shipment\PageObject\ShipmentListPage;
use SprykerTest\Zed\Shipment\ShipmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Shipment
 * @group Communication
 * @group Controller
 * @group ShipmentMethodEditCest
 * Add your own group annotations below this line
 */
class ShipmentMethodEditCest
{
    /**
     * @skip todo: refactor it in the next story
     *
     * @param \SprykerTest\Zed\Shipment\ShipmentCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ShipmentCommunicationTester $i)
    {
        $i->registerMoneyCollectionFormTypePlugin();

        $i->listDataTable(ShipmentListPage::URL . '/index/table');
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation('Dashboard / Shipment / Shipment Methods / Edit Shipment Method');
    }
}
