<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeProductLabelConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAlternativeCreateRequestTransfer;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorDependencyProvider;

/**
 * Auto-generated group annotations
 *
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
    /**
     * @var \SprykerTest\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->getFacade()->installProductAlternativeProductLabelConnector();
        $this->mockProductAlternativeFacadeDependency();
    }

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidResults(): void
    {
        // Arrange
        $this->tester->ensureTableProductAlternativeIsEmpty();
        $productConcreteTransfer = $this->tester->haveProduct();
        $alternativeProductTransfer = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setAlternativeSku($alternativeProductTransfer->getAbstractSku());
        $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        $this->tester->getProductAlternativeFacade()->persistProductAlternative($productConcreteTransfer);

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
    public function testUpdateAbstractProductWithAlternativesAvailableLabel(): void
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $productConcreteTransfer = $this->tester->haveProduct();
        $alternativeProductTransfer = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setAlternativeSku($alternativeProductTransfer->getAbstractSku());
        $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        $productAlternativeTransfer = $this->tester->getProductAlternativeFacade()->persistProductAlternative($productConcreteTransfer);
        $idProduct = $productAlternativeTransfer->getIdProductConcrete();

        // Act
        $this->tester->getFacade()->updateAbstractProductWithAlternativesAvailableLabel($idProduct);

        // Assert
        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return void
     */
    protected function mockProductAlternativeFacadeDependency(): void
    {
        $productAlternativeFacadeMock = $this->getMockBuilder(
            ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge::class
        )
            ->setConstructorArgs([$this->tester->getProductAlternativeFacade()])
            ->setMethods(['isAlternativeProductApplicable'])
            ->getMock();
        $productAlternativeFacadeMock
            ->method('isAlternativeProductApplicable')
            ->willReturn(true);

        $this->tester->setDependency(
            ProductAlternativeProductLabelConnectorDependencyProvider::FACADE_PRODUCT_ALTERNATIVE,
            $productAlternativeFacadeMock
        );
    }
}
