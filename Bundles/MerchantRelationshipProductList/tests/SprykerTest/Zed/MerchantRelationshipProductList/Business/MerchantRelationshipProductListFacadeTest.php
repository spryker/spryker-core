<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductList\Business;

use Codeception\Test\Unit;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationshipProductList
 * @group Business
 * @group Facade
 * @group MerchantRelationshipProductListFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipProductListFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testDeleteProductListsByMerchantRelationshipWillDeleteProductListsRelatedToMerchant(): void
    {
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $productListTransfer = $this->tester->createProductListWithMerchantRelationship($merchantRelationshipTransfer);

        /** @var \Spryker\Zed\MerchantRelationshipProductList\Business\MerchantRelationshipProductListFacadeInterface $merchantRelationshipProductListFacade */
        $merchantRelationshipProductListFacade = $this->tester->getFacade();
        $merchantRelationshipProductListFacade->deleteProductListsByMerchantRelationship($merchantRelationshipTransfer);

        $this->assertFalse(SpyProductListQuery::create()
            ->filterByIdProductList($productListTransfer->getIdProductList())
            ->exists());
    }

    /**
     * @return void
     */
    public function testGetAvailableProductListsForMerchantRelationshipReturnsUnassignedProductLists(): void
    {
        // Arrange
        $this->tester->truncateProductListTableRelations();
        $this->tester->clearProductListTable();

        $this->tester->createProductList();
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();

        // Act
        $productListCollectionTransfer = $this->tester->getFacade()
            ->getAvailableProductListsForMerchantRelationship($merchantRelationshipTransfer);

        // Assert
        $this->assertNotEmpty($productListCollectionTransfer->getProductLists());
    }

    /**
     * @return void
     */
    public function testGetAvailableProductListsForMerchantRelationshipReturnsProductListsAssignedToIt(): void
    {
        // Arrange
        $this->tester->truncateProductListTableRelations();
        $this->tester->clearProductListTable();

        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $this->tester->createProductListWithMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $productListCollectionTransfer = $this->tester->getFacade()
            ->getAvailableProductListsForMerchantRelationship($merchantRelationshipTransfer);

        // Assert
        $this->assertNotEmpty($productListCollectionTransfer->getProductLists());
    }

    /**
     * @return void
     */
    public function testGetAvailableProductListsForMerchantRelationshipDoesNotReturnProductListsAssignedToOtherMerchantRelationship(): void
    {
        // Arrange
        $this->tester->truncateProductListTableRelations();
        $this->tester->clearProductListTable();

        $firstMerchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $secondMerchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $this->tester->createProductListWithMerchantRelationship($firstMerchantRelationshipTransfer);

        // Act
        $productListCollectionTransfer = $this->tester->getFacade()
            ->getAvailableProductListsForMerchantRelationship($secondMerchantRelationshipTransfer);

        // Assert
        $this->assertEmpty($productListCollectionTransfer->getProductLists());
    }

    /**
     * @return void
     */
    public function testUpdateProductListMerchantRelationshipAssignmentsUnassignsMerchantRelationshipFromProductList(): void
    {
        // Arrange
        $this->tester->truncateProductListTableRelations();
        $this->tester->clearProductListTable();

        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $productListTransfer = $this->tester->createProductListWithMerchantRelationship($merchantRelationshipTransfer);

        $merchantRelationshipTransfer->setProductListIds([]);

        // Act
        $this->tester->getFacade()
            ->updateProductListMerchantRelationshipAssignments($merchantRelationshipTransfer);

        // Assert
        $updatedProductListTransfer = $this->tester->findProductListById($productListTransfer->getIdProductList());

        $this->assertNull($updatedProductListTransfer->getFkMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testUpdateProductListMerchantRelationshipAssignmentsAssignMerchantRelationshipToProductList(): void
    {
        // Arrange
        $this->tester->truncateProductListTableRelations();
        $this->tester->clearProductListTable();

        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $productListTransfer = $this->tester->createProductList();

        $merchantRelationshipTransfer->setProductListIds([
            $productListTransfer->getIdProductList(),
        ]);

        // Act
        $this->tester->getFacade()
            ->updateProductListMerchantRelationshipAssignments($merchantRelationshipTransfer);

        // Assert
        $updatedProductListTransfer = $this->tester->findProductListById($productListTransfer->getIdProductList());

        $this->assertSame($updatedProductListTransfer->getFkMerchantRelationship(), $merchantRelationshipTransfer->getIdMerchantRelationship());
    }
}
