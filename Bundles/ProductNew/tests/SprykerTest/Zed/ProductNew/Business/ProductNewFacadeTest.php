<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductNew\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ProductNew\Business\ProductNewBusinessFactory;
use Spryker\Zed\ProductNew\ProductNewConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductNew
 * @group Business
 * @group Facade
 * @group ProductNewFacadeTest
 * Add your own group annotations below this line
 */
class ProductNewFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductNew\ProductNewBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->cleanProductNewDates();
    }

    /**
     * @dataProvider validTimeRangeProductsToAssignDataProvider
     *
     * @param string $newFrom
     * @param string $newTo
     *
     * @return void
     */
    public function testFindProductsToAssignShouldReturnValidTimeRangeResults($newFrom, $newTo)
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => $newFrom,
            ProductAbstractTransfer::NEW_TO => $newTo,
        ]);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign(), 'Number of products to be assigned should have matched the expected amount.');
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeAssign(), 'Number of products to be deassigned should have matched the expected amount.');
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign()[0],
            'Product abstract to be assigned does not match expected ID.'
        );
    }

    /**
     * @dataProvider invalidTimeRangeProductsToAssignDataProvider
     *
     * @param string $newFrom
     * @param string $newTo
     *
     * @return void
     */
    public function testFindProductsToAssignShouldNotReturnInvalidTimeRangeResults($newFrom, $newTo)
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getLabelNewName(),
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => $newFrom,
            ProductAbstractTransfer::NEW_TO => $newTo,
        ]);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @dataProvider validTimeRangeProductsToAssignDataProvider
     *
     * @param string $newFrom
     * @param string $newTo
     *
     * @return void
     */
    public function testFindProductsToAssignShouldNotReturnAlreadyAssignedResults($newFrom, $newTo)
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => $newFrom,
            ProductAbstractTransfer::NEW_TO => $newTo,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productAbstractTransfer->getIdProductAbstract()
        );

        // Act
        $productLabelProductAbstractRelationTransfers = $this->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @dataProvider invalidTimeRangeProductsToAssignDataProvider
     *
     * @param string $newFrom
     * @param string $newTo
     *
     * @return void
     */
    public function testFindProductsToDeassignShouldReturnValidTimeRangeResults($newFrom, $newTo)
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => $newFrom,
            ProductAbstractTransfer::NEW_TO => $newTo,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productAbstractTransfer->getIdProductAbstract()
        );

        // Act
        $productLabelProductAbstractRelationTransfers = $this->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToAssign(), 'Number of products to be assigned should have matched the expected amount.');
        $this->assertCount(1, $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeAssign(), 'Number of products to be deassigned should have matched the expected amount.');
        $this->assertSame(
            $productAbstractTransfer->getIdProductAbstract(),
            $productLabelProductAbstractRelationTransfers[0]->getIdsProductAbstractToDeassign()[0],
            'Product abstract to be deassigned does not match expected ID.'
        );
    }

    /**
     * @dataProvider validTimeRangeProductsToAssignDataProvider
     *
     * @param string $newFrom
     * @param string $newTo
     *
     * @return void
     */
    public function testFindProductsToDeassignShouldNotReturnInvalidTimeRangeResults($newFrom, $newTo)
    {
        // Arrange
        $productLabelTransfer = $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getLabelNewName(),
        ]);
        $productAbstractTransfer = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => $newFrom,
            ProductAbstractTransfer::NEW_TO => $newTo,
        ]);
        $this->tester->haveProductLabelToAbstractProductRelation(
            $productLabelTransfer->getIdProductLabel(),
            $productAbstractTransfer->getIdProductAbstract()
        );

        // Act
        $productLabelProductAbstractRelationTransfers = $this->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @dataProvider invalidTimeRangeProductsToAssignDataProvider
     *
     * @param string $newFrom
     * @param string $newTo
     *
     * @return void
     */
    public function testFindProductsToDeassignShouldNotReturnNotAssignedResults($newFrom, $newTo)
    {
        // Arrange
        $this->tester->haveProductLabel([
            ProductLabelTransfer::NAME => $this->getLabelNewName(),
        ]);
        $this->tester->haveProductAbstract([
            ProductAbstractTransfer::NEW_FROM => $newFrom,
            ProductAbstractTransfer::NEW_TO => $newTo,
        ]);

        // Act
        $productLabelProductAbstractRelationTransfers = $this->getFacade()->findProductLabelProductAbstractRelationChanges();

        // Assert
        $this->assertCount(0, $productLabelProductAbstractRelationTransfers, 'Result should have been matched expected number of label relation changes.');
    }

    /**
     * @return string
     */
    protected function getLabelNewName()
    {
        return 'TEST_NEW_LABEL';
    }

    /**
     * @return \Spryker\Zed\ProductNew\Business\ProductNewFacadeInterface
     */
    protected function getFacade()
    {
        $configMock = $this->getMockBuilder(ProductNewConfig::class)->getMock();
        $configMock->method('getLabelNewName')->willReturn($this->getLabelNewName());

        $factory = new ProductNewBusinessFactory();
        $factory->setConfig($configMock);

        $facade = $this->tester->getFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return array
     */
    public function validTimeRangeProductsToAssignDataProvider()
    {
        return [
            'simple time range' => [
                date('Y-m-d H:i:s', strtotime('-1 day')),
                date('Y-m-d H:i:s', strtotime('+1 day')),
            ],
            'open from time range' => [
                null,
                date('Y-m-d H:i:s', strtotime('+1 day')),
            ],
            'open to time range' => [
                date('Y-m-d H:i:s', strtotime('-1 day')),
                null,
            ],
        ];
    }

    /**
     * @return array
     */
    public function invalidTimeRangeProductsToAssignDataProvider()
    {
        return [
            'time range in the future' => [
                date('Y-m-d H:i:s', strtotime('+1 day')),
                date('Y-m-d H:i:s', strtotime('+2 day')),
            ],
            'time range in the past' => [
                date('Y-m-d H:i:s', strtotime('-2 day')),
                date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            'open from time range in the past' => [
                null,
                date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            'open to time range in the future' => [
                date('Y-m-d H:i:s', strtotime('+1 day')),
                null,
            ],
            'time range not defined' => [
                null,
                null,
            ],
        ];
    }

    /**
     * @return void
     */
    protected function cleanProductNewDates()
    {
        $newFromFieldName = SpyProductAbstractTableMap::getTableMap()->getColumn(SpyProductAbstractTableMap::COL_NEW_FROM)->getPhpName();
        $newToFieldName = SpyProductAbstractTableMap::getTableMap()->getColumn(SpyProductAbstractTableMap::COL_NEW_TO)->getPhpName();

        SpyProductAbstractQuery::create()->update([
            $newFromFieldName => null,
            $newToFieldName => null,
        ]);
    }
}
