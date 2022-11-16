<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement\Communication\Controller;

use SprykerTest\Zed\ProductManagement\PageObject\ProductManagementProductListPage;
use SprykerTest\Zed\ProductManagement\ProductManagementCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductManagement
 * @group Communication
 * @group Controller
 * @group ProductManagementProductEditCest
 * Add your own group annotations below this line
 */
class ProductManagementProductEditCest
{
    /**
     * @var string
     */
    protected const PAGE_BREADCRUMB = 'Catalog / Products / Edit Product';

    /**
     * @param \SprykerTest\Zed\ProductManagement\ProductManagementCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbShouldBeVisibleWhenMoneyFormDoesNotHaveLocaleOption(ProductManagementCommunicationTester $i): void
    {
        $i->registerProductManagementStoreRelationFormTypePlugin();
        $i->registerMoneyCollectionFormTypePluginWithoutLocale();

        $i->listDataTable(ProductManagementProductListPage::URL_TABLE);
        $i->clickDataTableEditButton();
        $i->seeBreadcrumbNavigation(static::PAGE_BREADCRUMB);
    }
}
