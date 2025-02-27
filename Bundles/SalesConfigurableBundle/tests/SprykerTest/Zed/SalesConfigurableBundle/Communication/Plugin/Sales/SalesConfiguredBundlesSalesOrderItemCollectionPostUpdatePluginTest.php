<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesConfigurableBundle\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesConfigurableBundle\Communication\Plugin\Sales\SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePlugin;
use SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesConfigurableBundle
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesConfigurableBundle\SalesConfigurableBundleCommunicationTester
     */
    protected SalesConfigurableBundleCommunicationTester $tester;

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
    public function testShouldNotUpdateAnySalesOrderConfiguredBundles(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder();
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderConfiguredBundleQuery()->count());
        $this->assertSame(0, $this->tester->getSalesOrderConfiguredBundleItemQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateSalesOrderConfiguredBundle(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder(true);
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderConfiguredBundleEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldUpdateSalesOrderConfiguredBundle(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder(true);
        $this->tester->getFacade()->saveSalesOrderConfiguredBundlesFromQuote($quoteTransfer);
        $quoteTransfer->getItems()->offsetGet(0)->getConfiguredBundle()->getTemplate()
            ->setName('new-name')
            ->setUuid('new-uuid');

        $quoteTransfer->getItems()->offsetGet(0)->getConfiguredBundleItem()->getSlot()
            ->setUuid('new-uuid');

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderConfiguredBundleEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenIdSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createOrder(true);
        $quoteTransfer->getItems()->offsetGet(0)->setIdSalesOrderItem(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class));

        // Act
        (new SalesConfiguredBundlesSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertSalesOrderConfiguredBundleEntity(QuoteTransfer $quoteTransfer): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();

        $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();
        $configuredBundleItemTransfer = $itemTransfer->getConfiguredBundleItem();

        $salesOrderConfiguredBundleItemEntity = $this->tester->findSalesOrderConfiguredBundleItem($idSalesOrderItem);
        $salesOrderConfiguredBundleEntity = $salesOrderConfiguredBundleItemEntity->getSpySalesOrderConfiguredBundle();

        $this->assertSame($idSalesOrderItem, $salesOrderConfiguredBundleItemEntity->getFkSalesOrderItem());
        $this->assertSame(
            $configuredBundleItemTransfer->getSlot()->getUuid(),
            $salesOrderConfiguredBundleItemEntity->getConfigurableBundleTemplateSlotUuid(),
        );

        $this->assertSame($configuredBundleTransfer->getQuantity(), $salesOrderConfiguredBundleEntity->getQuantity());
        $this->assertSame(
            $configuredBundleTransfer->getTemplate()->getUuid(),
            $salesOrderConfiguredBundleEntity->getConfigurableBundleTemplateUuid(),
        );
        $this->assertSame(
            $configuredBundleTransfer->getTemplate()->getName(),
            $salesOrderConfiguredBundleEntity->getName(),
        );

        $this->assertSame(1, $this->tester->getSalesOrderConfiguredBundleQuery()->count());
        $this->assertSame(1, $this->tester->getSalesOrderConfiguredBundleItemQuery()->count());
    }
}
