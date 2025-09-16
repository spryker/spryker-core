<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDiscountConnector
 * @group Business
 * @group Facade
 * @group GetCategoryNamesIndexedByCategoryKeyTest
 * Add your own group annotations below this line
 */
class GetCategoryNamesIndexedByCategoryKeyTest extends Unit
{
    /**
     * @var string
     */
    protected const ROOT_CATEGORY_KEY = 'root-category-key';

    /**
     * @var string
     */
    protected const FIRST_CATEGORY_KEY = 'first-category-key';

    /**
     * @var string
     */
    protected const SECOND_CATEGORY_KEY = 'second-category-key';

    /**
     * @var \SprykerTest\Zed\CategoryDiscountConnector\CategoryDiscountConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCategoryNamesIndexedByCategoryKey(): void
    {
        // Arrange
        $rootCategory = $this->tester->haveLocalizedCategory([
            CategoryTransfer::CATEGORY_KEY => static::ROOT_CATEGORY_KEY,
        ]);
        $firstCategory = $this->tester->haveLocalizedCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $rootCategory->getCategoryNode(),
            CategoryTransfer::CATEGORY_KEY => static::FIRST_CATEGORY_KEY,
        ]);
        $secondCategory = $this->tester->haveLocalizedCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $firstCategory->getCategoryNode(),
            CategoryTransfer::CATEGORY_KEY => static::SECOND_CATEGORY_KEY,
        ]);
        $currentLocaleTransfer = $this->tester->getLocator()->locale()->facade()->getCurrentLocale();

        // Act
        $indexedCategories = $this->tester->getFacade()->getCategoryNamesIndexedByCategoryKey();

        // Assert
        $rootCategoryName = $rootCategory->getLocalizedAttributes()->offsetGet(1)->getName();
        $firstCategoryName = $firstCategory->getLocalizedAttributes()->offsetGet(1)->getName();
        $secondCategoryName = $secondCategory->getLocalizedAttributes()->offsetGet(1)->getName();

        $this->assertContains($rootCategoryName, array_values($indexedCategories));
        $this->assertContains($firstCategoryName, array_values($indexedCategories));
        $this->assertContains($secondCategoryName, array_values($indexedCategories));

        $this->assertSame($indexedCategories[static::SECOND_CATEGORY_KEY], $secondCategoryName);
    }
}
