<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Communication\Controller;

use SprykerTest\Zed\ProductOption\PageObject\ProductOptionCreatePage;
use SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductOption
 * @group Communication
 * @group Controller
 * @group ProductOptionCreateCest
 * Add your own group annotations below this line
 */
class ProductOptionCreateCest
{
    /**
     * @var string
     */
    protected const PAGE_BREADCRUMB = 'Catalog / Product Options / Create new Product Option';

    /**
     * @param \SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbShouldBeVisible(ProductOptionCommunicationTester $i): void
    {
        $i->registerMoneyCollectionFormTypePlugin();
        $this->executeBreadcrumbsVisibilityCheckSteps($i);
    }

    /**
     * @param \SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester $i
     *
     * @return void
     */
    public function breadcrumbShouldBeVisibleWhenMoneyFormDoesNotHaveLocaleOption(ProductOptionCommunicationTester $i): void
    {
        $i->registerMoneyCollectionFormTypePluginWithoutLocale();
        $this->executeBreadcrumbsVisibilityCheckSteps($i);
    }

    /**
     * @param \SprykerTest\Zed\ProductOption\ProductOptionCommunicationTester $i
     *
     * @return void
     */
    protected function executeBreadcrumbsVisibilityCheckSteps(ProductOptionCommunicationTester $i): void
    {
        $i->amOnPage(ProductOptionCreatePage::URL);
        $i->seeBreadcrumbNavigation(static::PAGE_BREADCRUMB);
    }
}
