<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Communication\Controller;

use SprykerTest\Zed\ProductRelation\PageObject\ProductRelationListPage;
use SprykerTest\Zed\ProductRelation\ProductRelationCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Communication
 * @group Controller
 * @group ProductRelationEditCest
 * Add your own group annotations below this line
 */
class ProductRelationEditCest
{
    /**
     * @param \SprykerTest\Zed\ProductRelation\ProductRelationCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductRelationCommunicationTester $i)
    {
        $i->listDataTable(ProductRelationListPage::URL . '/table');
        $i->clickDataTableEditButton();

        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Relations / Edit Product Relation');
    }
}
