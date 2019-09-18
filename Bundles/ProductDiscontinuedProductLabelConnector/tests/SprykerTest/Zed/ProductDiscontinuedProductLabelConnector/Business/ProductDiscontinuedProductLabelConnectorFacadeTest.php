<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedProductLabelConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductDiscontinuedProductLabelConnector
 * @group Business
 * @group Facade
 * @group ProductDiscontinuedProductLabelConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ProductDiscontinuedProductLabelConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->getFacade()->installProductDiscontinuedProductLabelConnector();
    }

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidResults(): void
    {
        // Arrange
        $this->tester->ensureTableProductDiscontinuedNoteIsEmpty();
        $this->tester->ensureTableProductDiscontinuedIsEmpty();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());
        $this->tester->getLocator()->productDiscontinued()->facade()
            ->markProductAsDiscontinued($productDiscontinueRequestTransfer);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->tester->getFacade()
            ->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertSame(
            (int)$productConcreteTransfer->getFkProductAbstract(),
            (int)$productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign()[0],
            'Product abstract to be assigned does not match expected ID.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateAbstractProductWithDiscontinuedLabel(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete());
        $this->tester->getLocator()->productDiscontinued()->facade()
            ->markProductAsDiscontinued($productDiscontinueRequestTransfer);
        $idProduct = $productConcreteTransfer->getIdProductConcrete();

        // Act
        $this->tester->getFacade()->updateAbstractProductWithDiscontinuedLabel($idProduct);

        // Assert
        $this->tester->assertDatabaseTableContainsData();
    }
}
