<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer;
use Generated\Shared\Transfer\GiftCardMetadataTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\GiftCard\Communication\Plugin\Sales\GiftCardOrderItemsPostSavePlugin;
use SprykerTest\Zed\GiftCard\GiftCardCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group GiftCardOrderItemsPostSavePluginTest
 * Add your own group annotations below this line
 */
class GiftCardOrderItemsPostSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardCommunicationTester
     */
    protected GiftCardCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->ensureSalesOrderItemGiftCardDatabaseTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testShouldNotCreateAnyGiftCards(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder();

        // Act
        (new GiftCardOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderItemGiftCardQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldNotCreateGiftCardWithIsGiftCardFalse(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder($this->createGiftCardMetadataTransfer(false));

        // Act
        (new GiftCardOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);

        // Assert
        $this->assertSame(0, $this->tester->getSalesOrderItemGiftCardQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateGiftCard(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder($this->createGiftCardMetadataTransfer());

        // Act
        (new GiftCardOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);

        // Assert
        $this->assertSalesOrderItemGiftCardEntity($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowPropelExceptionWhenIdSalesOrderItemIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = $this->createOrder($this->createGiftCardMetadataTransfer());
        $quoteTransfer->getItems()->offsetGet(0)->setIdSalesOrderItem(null);

        // Assert
        $this->expectException(PropelException::class);

        // Act
        (new GiftCardOrderItemsPostSavePlugin())->execute(new SaveOrderTransfer(), $quoteTransfer);
    }

    /**
     * @param bool|null $isGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardMetadataTransfer
     */
    protected function createGiftCardMetadataTransfer(?bool $isGiftCard = true): GiftCardMetadataTransfer
    {
        return (new GiftCardMetadataTransfer())
            ->setIsGiftCard($isGiftCard)
            ->setAbstractConfiguration(
                (new GiftCardAbstractProductConfigurationTransfer())->setCodePattern('code-pattern'),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function assertSalesOrderItemGiftCardEntity(QuoteTransfer $quoteTransfer): void
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $quoteTransfer->getItems()->offsetGet(0);
        $idSalesOrderItem = $itemTransfer->getIdSalesOrderItemOrFail();
        $giftCardMetadataTransfer = $itemTransfer->getGiftCardMetadata();

        $salesOrderItemGiftCardEntity = $this->tester->findSalesOrderItemGiftCard($idSalesOrderItem);

        $this->assertSame($idSalesOrderItem, $salesOrderItemGiftCardEntity->getFkSalesOrderItem());
        $this->assertSame(
            $giftCardMetadataTransfer->getAbstractConfiguration()->getCodePattern(),
            $salesOrderItemGiftCardEntity->getPattern(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardMetadataTransfer|null $giftCardMetadataTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createOrder(?GiftCardMetadataTransfer $giftCardMetadataTransfer = null): QuoteTransfer
    {
        if ($giftCardMetadataTransfer) {
            $giftCardMetadataTransfer->setAbstractConfiguration(
                (new GiftCardAbstractProductConfigurationTransfer())->setCodePattern('code-pattern'),
            );
        }

        $quoteTransfer = (new QuoteBuilder())
            ->withItem([
                ItemTransfer::GIFT_CARD_METADATA => $giftCardMetadataTransfer,
            ])
            ->withAnotherItem()
            ->withBillingAddress()
            ->withTotals()
            ->withCustomer()
            ->withCurrency()
            ->build();

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $quoteTransfer->setStore($storeTransfer);

        $saveOrderTransfer = $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);
        $quoteTransfer->setItems($saveOrderTransfer->getOrderItems());

        return $quoteTransfer;
    }
}
