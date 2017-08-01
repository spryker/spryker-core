<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Presentation;

use SprykerTest\Zed\ProductRelation\PageObject\ProductRelationCreatePage;
use SprykerTest\Zed\ProductRelation\PresentationTester;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Presentation
 * @group ProductRelationCreateCest
 * Add your own group annotations below this line
 */
class ProductRelationCreateCest
{

    /**
     * @param \SprykerTest\Zed\ProductRelation\PresentationTester $i
     *
     * @return void
     */
    public function breadcrumbIsVisible(PresentationTester $i)
    {
        $i->amOnPage(ProductRelationCreatePage::URL);
        $i->seeBreadcrumbNavigation('Dashboard / Products / Product Relations / Create new Product Relation');
    }

}
