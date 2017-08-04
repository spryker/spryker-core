<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Presentation;

use SprykerTest\Zed\ProductRelation\PageObject\ProductRelationListPage;
use SprykerTest\Zed\ProductRelation\ProductRelationPresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Presentation
 * @group ProductRelationViewCest
 * Add your own group annotations below this line
 */
class ProductRelationViewCest
{

    /**
     * @param \SprykerTest\Zed\ProductRelation\ProductRelationPresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(ProductRelationPresentationTester $i)
    {
        $i->amOnPage(ProductRelationListPage::URL);
        $i->clickDataTableViewButton();

        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Relations / View Product Relation');
    }

}
