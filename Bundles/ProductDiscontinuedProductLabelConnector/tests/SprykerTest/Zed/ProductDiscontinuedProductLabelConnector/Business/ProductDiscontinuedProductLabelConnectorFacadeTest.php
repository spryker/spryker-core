<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinuedProductLabelConnector\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelConnectorBusinessFactory;
use Spryker\Zed\ProductDiscontinuedProductLabelConnector\ProductDiscontinuedProductLabelConnectorConfig;

/**
 * Auto-generated group annotations
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
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidResults()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getProductDiscontinueLabelName(),
        ]);

        $productAbstractTransfer = $this->tester->haveProductAbstract(
            [ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 1]
        );

        $productDiscontinuedEntity = $this->getProductDiscontinuedQuery()
            ->filterByFkProduct(1)
            ->findOneOrCreate();

        $productDiscontinuedEntity->setActiveUntil((new DateTime())
            ->modify(sprintf('+%s Days', 180))
            ->format('Y-m-d'));

        $productDiscontinuedEntity->save();

        // Act
        $productLabelProductAbstractRelationTransfers = $this->productDiscontinuedProductLabelConnectorFacade()
            ->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(
            1,
            $productLabelProductAbstractRelationTransfers,
            'Result should have been matched expected number of label relation changes.'
        );
        $this->assertCount(
            1,
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign(),
            'Number of products to be assigned should have matched the expected amount.'
        );
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign()[0],
            'Product abstract to be assigned does not match expected ID.'
        );
    }

    /**
     * @return void
     */
    public function testFindProductsToDeAssignShouldReturnValidResults()
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getProductDiscontinueLabelName(),
        ]);

        $productAbstractTransfer = $this->tester->haveProductAbstract(
            [ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 146]
        );

        // Act
        $productLabelProductAbstractRelationTransfers = $this->productDiscontinuedProductLabelConnectorFacade()
            ->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(
            1,
            $productLabelProductAbstractRelationTransfers,
            'Result should have been matched expected number of label relation changes.'
        );
        $this->assertCount(
            2,
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeAssign(),
            'Number of products to be deassigned should have matched the expected amount.'
        );
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeAssign()[0],
            'Product abstract to be deassigned does not match expected ID.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateAbstractProductWithDiscontinuedLabel()
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $idProduct = 265;

        // Act
        $this->productDiscontinuedProductLabelConnectorFacade()->updateAbstractProductWithDiscontinuedLabel($idProduct);

        // Assert
        $this->tester->assertDatabaseTableContainsData();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        $configMock = $this->getMockBuilder(ProductDiscontinuedProductLabelConnectorConfig::class)->getMock();
        $configMock->method('getProductDiscontinueLabelName')
            ->willReturn($this->getProductDiscontinueLabelName());

        $factory = new ProductDiscontinuedProductLabelConnectorBusinessFactory();
        $factory->setConfig($configMock);

        $facade = $this->tester->getFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedProductLabelConnector\Business\ProductDiscontinuedProductLabelConnectorFacadeInterface
     */
    protected function productDiscontinuedProductLabelConnectorFacade()
    {
        return $this->tester->getLocator()->productDiscontinuedProductLabelConnector()->facade();
    }

    /**
     * @return string
     */
    protected function getProductDiscontinueLabelName()
    {
        return 'TEST_DISCONTINUED_LABEL';
    }

    /**
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery
     */
    protected function getProductDiscontinuedQuery(): SpyProductDiscontinuedQuery
    {
        return SpyProductDiscontinuedQuery::create();
    }
}
