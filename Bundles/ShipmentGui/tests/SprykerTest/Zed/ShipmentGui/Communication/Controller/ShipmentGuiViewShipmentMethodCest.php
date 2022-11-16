<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentGui\Communication\Controller;

use SprykerTest\Zed\ShipmentGui\PageObject\ShipmentGuiListShipmentPage;
use SprykerTest\Zed\ShipmentGui\ShipmentGuiCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentGui
 * @group Communication
 * @group Controller
 * @group ShipmentGuiViewShipmentMethodCest
 * Add your own group annotations below this line
 */
class ShipmentGuiViewShipmentMethodCest
{
    /**
     * @var string
     */
    protected const PAGE_BREADCRUMB = 'Administration / Delivery Methods / View';

    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbShouldBeVisible(ShipmentGuiCommunicationTester $i): void
    {
        $i->registerMoneyCollectionFormTypePlugin();
        $this->executeBreadcrumbsVisibilityCheckSteps($i);
    }

    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbShouldBeVisibleWhenMoneyFormDoesNotHaveLocaleOption(ShipmentGuiCommunicationTester $i): void
    {
        $i->registerMoneyCollectionFormTypePluginWithoutLocale();
        $this->executeBreadcrumbsVisibilityCheckSteps($i);
    }

    /**
     * @param \SprykerTest\Zed\ShipmentGui\ShipmentGuiCommunicationTester $i
     *
     * @return void
     */
    protected function executeBreadcrumbsVisibilityCheckSteps(ShipmentGuiCommunicationTester $i): void
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->listDataTable(ShipmentGuiListShipmentPage::URL_TABLE);
        $i->clickDataTableViewButton();
        $i->seeBreadcrumbNavigation(static::PAGE_BREADCRUMB);
    }
}
