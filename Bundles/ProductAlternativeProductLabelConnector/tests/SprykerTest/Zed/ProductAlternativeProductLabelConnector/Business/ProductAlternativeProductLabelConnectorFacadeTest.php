<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeProductLabelConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorBusinessFactory;
use Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig;

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
            ProductLabelTransfer::NAME => $this->getProductAlternativeLabelName(),
        ]);
        $productConcreteTransfer = $this->tester->haveProduct();
        $productAlternativeEntity = $this->createProductAlternativeQuery()
            ->filterByFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();
        $productAlternativeEntity->save();

        // Act
        $productLabelProductAbstractRelationTransfers = $this->productAlternativeProductLabelConnectorFacade()
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
        $productAlternativeEntity = $this->createProductAlternativeQuery()
            ->filterByFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();
        $productAlternativeEntity->save();
        $idProduct = $productAlternativeEntity->getFkProduct();

        // Act
        $this->productAlternativeProductLabelConnectorFacade()
            ->updateAbstractProductWithAlternativesAvailableLabel($idProduct);

        // Assert
        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        $configMock = $this->getMockBuilder(ProductAlternativeProductLabelConnectorConfig::class)->getMock();
        $configMock->method('getProductAlternativeLabelName')
            ->willReturn($this->getProductAlternativeLabelName());

        $factory = new ProductAlternativeProductLabelConnectorBusinessFactory();
        $factory->setConfig($configMock);

        $facade = $this->tester->getFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorFacadeInterface
     */
    protected function productAlternativeProductLabelConnectorFacade()
    {
        return $this->tester->getLocator()->productAlternativeProductLabelConnector()->facade();
    }

    /**
     * @return string
     */
    protected function getProductAlternativeLabelName()
    {
        return 'TEST_ALTERNATIVE_LABEL';
    }

    /**
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery
     */
    protected function createProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }
}
