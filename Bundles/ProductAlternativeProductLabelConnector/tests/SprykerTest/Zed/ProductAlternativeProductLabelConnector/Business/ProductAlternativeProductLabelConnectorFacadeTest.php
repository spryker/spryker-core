<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeProductLabelConnector\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\ProductAbstractTransfer;
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
            ProductLabelTransfer::NAME => $this->getProductAlternativeLabelName(),
        ]);

        $productAbstractTransfer = $this->tester->haveProductAbstract(
            [ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 1]
        );
        $productAlternativeEntity = $this->getProductAlternativeQuery()
            ->filterByFkProduct(1)
            ->findOneOrCreate();

        $productAlternativeEntity->setFkProductConcreteAlternative(1);
        $productAlternativeEntity->save();

        // Act
        $productLabelProductAbstractRelationTransfers = $this->productAlternativeProductLabelConnectorFacade()
            ->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(
            1, $productLabelProductAbstractRelationTransfers,
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
            ProductLabelTransfer::NAME => $this->getProductAlternativeLabelName(),
        ]);

        $productAbstractTransfer = $this->tester->haveProductAbstract(
            [ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => 155]
        );

        // Act
        $productLabelProductAbstractRelationTransfers = $this->productAlternativeProductLabelConnectorFacade()
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
    public function testUpdateAbstractProductWithAlternativesAvailableLabel()
    {
        // Arrange
        $this->tester->ensureDatabaseTableIsEmpty();
        $idProduct = 201;

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
    protected function getProductAlternativeQuery(): SpyProductAlternativeQuery
    {
        return SpyProductAlternativeQuery::create();
    }
}
