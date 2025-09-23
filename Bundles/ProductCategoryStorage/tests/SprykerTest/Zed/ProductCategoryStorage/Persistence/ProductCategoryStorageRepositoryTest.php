<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductCategoryStorage\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductCategoryStorage
 * @group Persistence
 * @group ProductCategoryStorageRepositoryTest
 * Add your own group annotations below this line
 */
class ProductCategoryStorageRepositoryTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerTest\Zed\ProductCategoryStorage\ProductCategoryStoragePersistenceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetAllCategoryNodeAggregationsOrderedByDescendantWillReturnCategoriesInCorrectOrder(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $rootCategoryNodeTransfer = $this->tester->getRootCategoryNode();

        $categoryTransferLevelOne = $this->tester->haveLocalizedCategoryTransferWithStoreRelation(
            $rootCategoryNodeTransfer,
            $storeTransfer,
        );
        $categoryTransferLevelTwo = $this->tester->haveLocalizedCategoryTransferWithStoreRelation(
            $categoryTransferLevelOne->getCategoryNode(),
            $storeTransfer,
        );
        $categoryTransferLevelThree = $this->tester->haveLocalizedCategoryTransferWithStoreRelation(
            $categoryTransferLevelTwo->getCategoryNode(),
            $storeTransfer,
        );

        // Act
        $categoryNodeAggregationTransfers = (new ProductCategoryStorageRepository())->getAllCategoryNodeAggregationsOrderedByDescendant();

        // Assert
        $groupedCategoryNodeAggregationTransfers = $this->getCategoryNodeAggregationTransfersGroupedByIdCategoryNodeDescendant($categoryNodeAggregationTransfers);
        $idCategoryNodeLevelThree = $categoryTransferLevelThree->getCategoryNode()->getIdCategoryNode();

        $this->assertArrayHasKey($idCategoryNodeLevelThree, $groupedCategoryNodeAggregationTransfers);
        $this->assertCount(6, $groupedCategoryNodeAggregationTransfers[$idCategoryNodeLevelThree]);
        $this->assertSame(
            $idCategoryNodeLevelThree,
            (int)$groupedCategoryNodeAggregationTransfers[$idCategoryNodeLevelThree][0]->getIdCategoryNode(),
        );
        $this->assertSame(
            $categoryTransferLevelTwo->getCategoryNode()->getIdCategoryNode(),
            (int)$groupedCategoryNodeAggregationTransfers[$idCategoryNodeLevelThree][3]->getIdCategoryNode(),
        );
        $this->assertSame(
            $categoryTransferLevelOne->getCategoryNode()->getIdCategoryNode(),
            (int)$groupedCategoryNodeAggregationTransfers[$idCategoryNodeLevelThree][5]->getIdCategoryNode(),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\CategoryNodeAggregationTransfer> $categoryNodeAggregationTransfers
     *
     * @return array<int, array<<\Generated\Shared\Transfer\CategoryNodeAggregationTransfer>>>
     */
    protected function getCategoryNodeAggregationTransfersGroupedByIdCategoryNodeDescendant(array $categoryNodeAggregationTransfers): array
    {
        $groupedCategoryNodeAggregationTransfers = [];
        foreach ($categoryNodeAggregationTransfers as $categoryNodeAggregationTransfer) {
            $idCategoryNodeDescendant = $categoryNodeAggregationTransfer->getIdCategoryNodeDescendant();

            $groupedCategoryNodeAggregationTransfers[$idCategoryNodeDescendant][] = $categoryNodeAggregationTransfer;
        }

        return $groupedCategoryNodeAggregationTransfers;
    }
}
