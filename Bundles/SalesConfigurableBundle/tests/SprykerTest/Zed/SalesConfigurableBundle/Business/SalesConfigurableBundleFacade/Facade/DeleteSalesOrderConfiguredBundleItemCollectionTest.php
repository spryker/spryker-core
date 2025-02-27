<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesConfigurableBundle\Business\SalesConfigurableBundleFacade\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesConfigurableBundle
 * @group Business
 * @group SalesConfigurableBundleFacade
 * @group Facade
 * @group DeleteSalesOrderConfiguredBundleItemCollectionTest
 * Add your own group annotations below this line
 */
class DeleteSalesOrderConfiguredBundleItemCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleBusinessTester
     */
    protected SalesConfigurableBundleBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderConfiguredBundleItemDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldDeleteSalesOrderConfiguredBundlesEntities(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder(true);
        $this->tester->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer = (new SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem($quoteTransfer->getItems()->offsetGet(0)->getIdSalesOrderItemOrFail());

        // Act
        $this->tester->getFacade()->deleteSalesOrderConfiguredBundleItemCollection(
            $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer,
        );

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderConfiguredBundleQuery()->count());
        $this->assertSame(0, $this->tester->getSalesOrderConfiguredBundleItemQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldNotDeleteSalesOrderConfiguredBundleEntitiesWhenNoEntitiesFoundBySalesOrderItemIds(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder(true);
        $this->tester->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer = (new SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer())
            ->addIdSalesOrderItem(-1);

        // Act
        $this->tester->getFacade()->deleteSalesOrderConfiguredBundleItemCollection(
            $salesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer,
        );

        // Assert
        $this->assertSame(1, $this->tester->getSalesOrderConfiguredBundleQuery()->count());
        $this->assertSame(1, $this->tester->getSalesOrderConfiguredBundleItemQuery()->count());
    }

    /**
     * @return void
     */
    public function testDoesNotDeleteSalesOrderConfiguredBundleItemEntitiesWhenNoCriteriaConditionsAreSet(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder(true);
        $this->tester->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);

        // Act
        $this->tester->getFacade()->deleteSalesOrderConfiguredBundleItemCollection(
            new SalesOrderConfiguredBundleItemCollectionDeleteCriteriaTransfer(),
        );

        // Assert
        $this->assertSame(1, $this->tester->getSalesOrderConfiguredBundleQuery()->count());
        $this->assertSame(1, $this->tester->getSalesOrderConfiguredBundleItemQuery()->count());
    }
}
