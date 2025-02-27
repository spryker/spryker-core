<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesProductConnector\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesProductConnector\Communication\Plugin\Sales\ItemMetadataSalesOrderItemCollectionPostUpdatePlugin;
use SprykerTest\Zed\SalesProductConnector\SalesProductConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesProductConnector
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group ItemMetadataSalesOrderItemCollectionPostUpdatePluginTest
 * Add your own group annotations below this line
 */
class ItemMetadataSalesOrderItemCollectionPostUpdatePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\SalesProductConnector\SalesProductConnectorCommunicationTester
     */
    protected SalesProductConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemMetadataDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldCreateItemMetadata(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder(true);
        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new ItemMetadataSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderItemItemMetadataEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldUpdateItemMetadata(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder(true);
        $this->tester->getFacade()->saveOrderItemMetadata($quoteTransfer, new SaveOrderTransfer());
        $quoteTransfer->getItems()->offsetGet(0)->getImages()->offsetGet(0)->setExternalUrlSmall('new-image-url');

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Act
        (new ItemMetadataSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);

        // Assert
        $this->assertSalesOrderItemItemMetadataEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenIdSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder(true);
        $quoteTransfer->getItems()->offsetGet(0)->setIdSalesOrderItem(null);

        $salesOrderItemCollectionResponseTransfer = (new SalesOrderItemCollectionResponseTransfer())
            ->setItems($quoteTransfer->getItems());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "idSalesOrderItem" of transfer `%s` is null.', ItemTransfer::class));

        // Act
        (new ItemMetadataSalesOrderItemCollectionPostUpdatePlugin())->postUpdate($salesOrderItemCollectionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertSalesOrderItemItemMetadataEntity(QuoteTransfer $quoteTransfer): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();

        $salesOrderItemItemMetadataEntity = $this->tester->findSalesOrderItemMetadata($idSalesOrderItem);

        $this->assertSame($idSalesOrderItem, $salesOrderItemItemMetadataEntity->getFkSalesOrderItem());
        $this->assertSame('[]', $salesOrderItemItemMetadataEntity->getSuperAttributes());
        $this->assertSame(
            $itemTransfer->getImages()->offsetGet(0)->getExternalUrlSmall(),
            $salesOrderItemItemMetadataEntity->getImage(),
        );
    }

    /**
     * @param bool|null $withMetadata
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createOrder(?bool $withMetadata = false): QuoteTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        if ($withMetadata) {
            $quoteTransfer->getItems()->offsetGet(0)->addImage((new ProductImageBuilder())->build());
        }

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setItems($saveOrderTransfer->getOrderItems());

        return $quoteTransfer;
    }
}
