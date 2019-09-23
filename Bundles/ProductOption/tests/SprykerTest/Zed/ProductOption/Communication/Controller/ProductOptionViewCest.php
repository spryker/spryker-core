<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Communication\Controller;

use SprykerTest\Zed\ProductOption\PageObject\ProductOptionListPage;
use SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Communication
 * @group Controller
 * @group ProductOptionViewCest
 * Add your own group annotations below this line
 */
class ProductOptionViewCest
{
    /**
     * @param \SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductOptionCommunicationTester $i)
    {
        $i->listDataTable(ProductOptionListPage::URL . '/list-table');
        $i->clickDataTableViewButton();
        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Options / View Product Option');
    }
}
