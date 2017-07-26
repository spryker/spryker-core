<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Presentation;

use SprykerTest\Zed\ProductRelation\PageObject\ProductRelationListPage;
use SprykerTest\Zed\ProductRelation\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Presentation
 * @group ProductRelationEditCest
 * Add your own group annotations below this line
 */
class ProductRelationEditCest
{

    /**
     * @param \SprykerTest\Zed\ProductRelation\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(ProductRelationListPage::URL);
        $i->wait(2);
        $i->click('(//a[contains(., "Edit")])[1]');
        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Relations / Edit Product Relation');
    }

}
