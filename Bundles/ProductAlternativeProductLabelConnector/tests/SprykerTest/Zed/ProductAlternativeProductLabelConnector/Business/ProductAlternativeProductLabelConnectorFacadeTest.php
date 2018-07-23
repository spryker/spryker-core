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
use Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade\ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorDependencyProvider;

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
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternativeFacadeInterface
     */
    protected $productAlternativeFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productAlternativeFacade = $this->tester->getLocator()->productAlternative()->facade();
        $this->mockProductAlternativeFacade();
    }

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidResults(): void
    {
        // Arrange
        $this->tester->ensureTableProductAlternativeIsEmpty();
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => self::TEST_ALTERNATIVE_LABEL,
        ]);
        $productConcreteTransfer = $this->tester->haveProduct();
        $alternativeProductTransfer = $this->tester->haveProduct();
        $productAlternativeCreateRequestTransfer = (new ProductAlternativeCreateRequestTransfer())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setAlternativeSku($alternativeProductTransfer->getAbstractSku());
        $productConcreteTransfer->addProductAlternativeCreateRequest($productAlternativeCreateRequestTransfer);
        $this->productAlternativeFacade->persistProductAlternative($productConcreteTransfer);

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
        $productAlternativeTransfer = $this->productAlternativeFacade->persistProductAlternative($productConcreteTransfer);
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

    /**
     * @return void
     */
    protected function mockProductAlternativeFacade(): void
    {
        $productAlternativeFacadeMock = $this->getMockBuilder(
            ProductAlternativeProductLabelConnectorToProductAlternativeFacadeBridge::class
        )
            ->setConstructorArgs([$this->productAlternativeFacade])
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
