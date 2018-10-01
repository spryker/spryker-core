<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Communication\Controller;

use SprykerTest\Zed\ProductRelation\PageObject\ProductRelationCreatePage;
use SprykerTest\Zed\ProductRelation\ProductRelationCommunicationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Communication
 * @group Controller
 * @group ProductRelationCreateCest
 * Add your own group annotations below this line
 */
class ProductRelationCreateCest
{
    /**
     * @param \SprykerTest\Zed\ProductRelation\ProductRelationCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductRelationCommunicationTester $i)
    {
        $i->amOnPage(ProductRelationCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Relations / Create new Product Relation');
    }
}
