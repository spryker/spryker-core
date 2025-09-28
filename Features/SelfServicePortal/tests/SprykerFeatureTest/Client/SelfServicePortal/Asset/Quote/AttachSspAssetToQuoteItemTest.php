<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Client\SelfServicePortal\Asset\Quote;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\Quote\QuoteClientInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\DatabaseQuoteStorageStrategy;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteItemFinderInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteStorageStrategyInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Quote\SessionQuoteStorageStrategy;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClient;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory;
use SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStubInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group Asset
 * @group Quote
 * @group AttachSspAssetToQuoteItemTest
 * Add your own group annotations below this line
 */
class AttachSspAssetToQuoteItemTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SSP_ASSET_REFERENCE = 'TEST_ASSET_001';

    /**
     * @var string
     */
    protected const TEST_SKU = 'TEST_SKU_001';

    /**
     * @var string
     */
    protected const TEST_GROUP_KEY = 'TEST_GROUP_001';

    /**
     * @var int
     */
    protected const TEST_QUOTE_ID = 1;

    /**
     * @var \SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester
     */
    protected $tester;

    public function testAttachSspAssetToQuoteItemWithSessionStorageStrategySuccess(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithItem();
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();

        $quoteClientMock = $this->createQuoteClientMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);
        $quoteClientMock->method('setQuote')->willReturnSelf();

        $quoteItemFinderPluginMock = $this->createQuoteItemFinderPluginMock();
        $quoteItemFinderPluginMock->method('findItem')->willReturn($quoteTransfer->getItems()->getIterator()->current());

        $sessionQuoteStorageStrategy = new SessionQuoteStorageStrategy($quoteClientMock, $quoteItemFinderPluginMock);

        $selfServicePortalClient = $this->createSelfServicePortalClientWithStorageStrategy($sessionQuoteStorageStrategy);

        // Act
        $quoteResponseTransfer = $selfServicePortalClient->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotNull($quoteTransfer->getItems()->getIterator()->current()->getSspAsset());
        $this->assertEquals(static::TEST_SSP_ASSET_REFERENCE, $quoteTransfer->getItems()->getIterator()->current()->getSspAsset()->getReference());
    }

    public function testAttachSspAssetToQuoteItemWithSessionStorageStrategyItemNotFound(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithItem();
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();

        $quoteClientMock = $this->createQuoteClientMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);

        $quoteItemFinderPluginMock = $this->createQuoteItemFinderPluginMock();
        $quoteItemFinderPluginMock->method('findItem')->willReturn(null);

        $sessionQuoteStorageStrategy = new SessionQuoteStorageStrategy($quoteClientMock, $quoteItemFinderPluginMock);

        $selfServicePortalClient = $this->createSelfServicePortalClientWithStorageStrategy($sessionQuoteStorageStrategy);

        // Act
        $quoteResponseTransfer = $selfServicePortalClient->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    public function testAttachSspAssetToQuoteItemWithSessionStorageStrategyRemoveAsset(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithItem();
        $quoteTransfer->getItems()->getIterator()->current()->setSspAsset(
            (new SspAssetTransfer())->setReference(static::TEST_SSP_ASSET_REFERENCE),
        );

        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $sspAssetQuoteItemAttachmentRequestTransfer->setSspAssetReference(null);

        $quoteClientMock = $this->createQuoteClientMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);
        $quoteClientMock->method('setQuote')->willReturnSelf();

        $quoteItemFinderPluginMock = $this->createQuoteItemFinderPluginMock();
        $quoteItemFinderPluginMock->method('findItem')->willReturn($quoteTransfer->getItems()->getIterator()->current());

        $sessionQuoteStorageStrategy = new SessionQuoteStorageStrategy($quoteClientMock, $quoteItemFinderPluginMock);

        $selfServicePortalClient = $this->createSelfServicePortalClientWithStorageStrategy($sessionQuoteStorageStrategy);

        // Act
        $quoteResponseTransfer = $selfServicePortalClient->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertNull($quoteTransfer->getItems()->getIterator()->current()->getSspAsset());
    }

    public function testAttachSspAssetToQuoteItemWithDatabaseStorageStrategySuccess(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithItem();
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();

        $quoteClientMock = $this->createQuoteClientMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);
        $quoteClientMock->method('setQuote')->willReturnSelf();

        $expectedQuoteResponseTransfer = new QuoteResponseTransfer();
        $expectedQuoteResponseTransfer->setIsSuccessful(true);
        $expectedQuoteResponseTransfer->setQuoteTransfer($quoteTransfer);

        $selfServicePortalStubMock = $this->createSelfServicePortalStubMock();
        $selfServicePortalStubMock->method('attachSspAssetToQuoteItem')->willReturn($expectedQuoteResponseTransfer);

        $databaseQuoteStorageStrategy = new DatabaseQuoteStorageStrategy($quoteClientMock, $selfServicePortalStubMock);

        $selfServicePortalClient = $this->createSelfServicePortalClientWithStorageStrategy($databaseQuoteStorageStrategy);

        // Act
        $quoteResponseTransfer = $selfServicePortalClient->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    public function testAttachSspAssetToQuoteItemWithDatabaseStorageStrategyNoCustomer(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithItem();
        $quoteTransfer->setCustomer(null);
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();

        $quoteClientMock = $this->createQuoteClientMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);

        $selfServicePortalStubMock = $this->createSelfServicePortalStubMock();

        $databaseQuoteStorageStrategy = new DatabaseQuoteStorageStrategy($quoteClientMock, $selfServicePortalStubMock);

        $selfServicePortalClient = $this->createSelfServicePortalClientWithStorageStrategy($databaseQuoteStorageStrategy);

        // Act
        $quoteResponseTransfer = $selfServicePortalClient->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    public function testAttachSspAssetToQuoteItemWithDatabaseStorageStrategyStubFailure(): void
    {
        // Arrange
        $quoteTransfer = $this->createQuoteTransferWithItem();
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();

        $quoteClientMock = $this->createQuoteClientMock();
        $quoteClientMock->method('getQuote')->willReturn($quoteTransfer);

        $expectedQuoteResponseTransfer = new QuoteResponseTransfer();
        $expectedQuoteResponseTransfer->setIsSuccessful(false);

        $selfServicePortalStubMock = $this->createSelfServicePortalStubMock();
        $selfServicePortalStubMock->method('attachSspAssetToQuoteItem')->willReturn($expectedQuoteResponseTransfer);

        $databaseQuoteStorageStrategy = new DatabaseQuoteStorageStrategy($quoteClientMock, $selfServicePortalStubMock);

        $selfServicePortalClient = $this->createSelfServicePortalClientWithStorageStrategy($databaseQuoteStorageStrategy);

        // Act
        $quoteResponseTransfer = $selfServicePortalClient->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    protected function createQuoteTransferWithItem(): QuoteTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_GROUP_KEY);

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer(1);

        return (new QuoteTransfer())
            ->setIdQuote(static::TEST_QUOTE_ID)
            ->setCustomer($customerTransfer)
            ->setItems(new ArrayObject([$itemTransfer]));
    }

    protected function createSspAssetQuoteItemAttachmentRequestTransfer(): SspAssetQuoteItemAttachmentRequestTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_GROUP_KEY);

        return (new SspAssetQuoteItemAttachmentRequestTransfer())
            ->setSspAssetReference(static::TEST_SSP_ASSET_REFERENCE)
            ->setItem($itemTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Client\Quote\QuoteClientInterface
     */
    protected function createQuoteClientMock(): QuoteClientInterface
    {
        return $this->createMock(QuoteClientInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Client\SelfServicePortal\Asset\Quote\QuoteItemFinderInterface
     */
    protected function createQuoteItemFinderPluginMock(): QuoteItemFinderInterface
    {
        return $this->createMock(QuoteItemFinderInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Client\SelfServicePortal\Zed\SelfServicePortalStubInterface
     */
    protected function createSelfServicePortalStubMock(): SelfServicePortalStubInterface
    {
        return $this->createMock(SelfServicePortalStubInterface::class);
    }

    protected function createSelfServicePortalClientWithStorageStrategy(QuoteStorageStrategyInterface $storageStrategy): SelfServicePortalClient
    {
        $factoryMock = $this->createMock(SelfServicePortalFactory::class);
        $factoryMock->method('getQuoteStorageStrategy')->willReturn($storageStrategy);

        $selfServicePortalClient = new SelfServicePortalClient();
        $selfServicePortalClient->setFactory($factoryMock);

        return $selfServicePortalClient;
    }
}
