<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group GetAscendantCategoryKeysGroupedByIdCategoryNodeTest
 * Add your own group annotations below this line
 */
class GetAscendantCategoryKeysGroupedByIdCategoryNodeTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_CATEGORY_ROOT = 1;

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetAscendantCategoryKeysGroupedByIdCategoryNodeChecksGroupedCategoryKeys(): void
    {
        // Arrange
        $rootCategory = $this->tester
            ->getFacade()
            ->findCategory((new CategoryCriteriaTransfer())->setIdCategory(static::ID_CATEGORY_ROOT));

        $firstCategory = $this->tester->haveCategory([CategoryTransfer::PARENT_CATEGORY_NODE => $rootCategory->getCategoryNode()]);
        $secondCategory = $this->tester->haveCategory([CategoryTransfer::PARENT_CATEGORY_NODE => $firstCategory->getCategoryNode()]);

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategoryNode($rootCategory->getCategoryNode()->getIdCategoryNode())
            ->addIdCategoryNode($firstCategory->getCategoryNode()->getIdCategoryNode())
            ->addIdCategoryNode($secondCategory->getCategoryNode()->getIdCategoryNode());

        // Act
        $indexedCategoryKeys = $this->tester
            ->getFacade()
            ->getAscendantCategoryKeysGroupedByIdCategoryNode($categoryNodeCriteriaTransfer);

        // Assert
        $this->assertCount(3, $indexedCategoryKeys);

        $this->assertSame(
            [$rootCategory->getCategoryKey(), $firstCategory->getCategoryKey(), $secondCategory->getCategoryKey()],
            $indexedCategoryKeys[$secondCategory->getCategoryNode()->getIdCategoryNode()],
        );
        $this->assertSame(
            [$rootCategory->getCategoryKey(), $firstCategory->getCategoryKey()],
            $indexedCategoryKeys[$firstCategory->getCategoryNode()->getIdCategoryNode()],
        );
        $this->assertSame(
            [$rootCategory->getCategoryKey()],
            $indexedCategoryKeys[$rootCategory->getCategoryNode()->getIdCategoryNode()],
        );
    }

    /**
     * @return void
     */
    public function testGetAscendantCategoryKeysGroupedByIdCategoryNodeChecksCategoryWithoutParent(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        $indexedCategoryKeys = $this->tester
            ->getFacade()
            ->getAscendantCategoryKeysGroupedByIdCategoryNode($categoryNodeCriteriaTransfer);

        // Assert
        $this->assertCount(1, $indexedCategoryKeys);
        $this->assertSame(
            [$categoryTransfer->getCategoryKey()],
            $indexedCategoryKeys[$categoryTransfer->getCategoryNode()->getIdCategoryNode()],
        );
    }

    /**
     * @return void
     */
    public function testGetAscendantCategoryKeysGroupedByIdCategoryNodeWithoutFilteringByIdCategoryNode(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();

        // Act
        $indexedCategoryKeys = $this->tester
            ->getFacade()
            ->getAscendantCategoryKeysGroupedByIdCategoryNode(new CategoryNodeCriteriaTransfer());

        // Assert
        $this->assertSame(
            [$categoryTransfer->getCategoryKey()],
            $indexedCategoryKeys[$categoryTransfer->getCategoryNode()->getIdCategoryNode()],
        );
    }
}
