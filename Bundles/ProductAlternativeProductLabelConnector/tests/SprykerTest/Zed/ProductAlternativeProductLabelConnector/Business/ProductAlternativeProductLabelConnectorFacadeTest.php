<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeProductLabelConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductAlternativeProductLabelConnector
 * @group Business
 * @group Facade
 * @group ProductAlternativeProductLabelConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductAlternativeProductLabelConnectorFacadeTest extends Unit
{
    protected const TEST_ALTERNATIVE_LABEL = 'TEST_ALTERNATIVE_LABEL';

    /**
     * @var \SprykerTest\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidResults()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => self::TEST_ALTERNATIVE_LABEL,
        ]);
        $productConcreteTransfer = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setAlternativeSku($productConcreteTransfer->getAbstractSku());
        $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        $this->tester->getLocator()
            ->productAlternative()
            ->facade()
            ->persistProductAlternative($productConcreteTransfer);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()
            ->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertSame(
            $productConcreteTransfer->getFkProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign()[0],
            'Product abstract to be assigned does not match expected ID.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateAbstractProductWithAlternativesAvailableLabel()
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setAlternativeSku($productConcreteTransfer->getAbstractSku());
        $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        $productAlternativeTransfer = $this->tester->getLocator()
            ->productAlternative()
            ->facade()
            ->persistProductAlternative($productConcreteTransfer);
        $idProduct = $productAlternativeTransfer->getIdProductConcrete();

        // Act
        $this->tester->getFacade()->updateAbstractProductWithAlternativesAvailableLabel($idProduct);

        // Assert
        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function createProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }
}
