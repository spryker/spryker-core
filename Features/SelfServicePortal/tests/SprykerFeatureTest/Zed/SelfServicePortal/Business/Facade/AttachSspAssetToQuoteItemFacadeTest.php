<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote\QuoteItemFinderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote\SspAssetQuoteItemSetter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote\SspAssetQuoteItemSetterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacade;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group AttachSspAssetToQuoteItemFacadeTest
 * Add your own group annotations below this line
 */
class AttachSspAssetToQuoteItemFacadeTest extends Unit
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
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'CUSTOMER_001';

    protected SelfServicePortalBusinessTester $tester;

    protected QuoteTransfer $quoteTransfer;

    protected StoreTransfer $storeTransfer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $this->tester->haveCustomer(),
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testAttachSspAssetToQuoteItemSuccess(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $expectedQuoteResponseTransfer = $this->createSuccessfulQuoteResponseTransfer();

        $sspAssetQuoteItemSetterMock = $this->createSspAssetQuoteItemSetterMock();
        $sspAssetQuoteItemSetterMock->method('setSspAssetToQuoteItem')
            ->with($sspAssetQuoteItemAttachmentRequestTransfer)
            ->willReturn($expectedQuoteResponseTransfer);

        $facade = $this->createSelfServicePortalFacadeWithSetter($sspAssetQuoteItemSetterMock);

        // Act
        $quoteResponseTransfer = $facade->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($expectedQuoteResponseTransfer, $quoteResponseTransfer);
    }

    public function testAttachSspAssetToQuoteItemFailure(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $expectedQuoteResponseTransfer = $this->createFailedQuoteResponseTransfer();

        $sspAssetQuoteItemSetterMock = $this->createSspAssetQuoteItemSetterMock();
        $sspAssetQuoteItemSetterMock->method('setSspAssetToQuoteItem')
            ->with($sspAssetQuoteItemAttachmentRequestTransfer)
            ->willReturn($expectedQuoteResponseTransfer);

        $facade = $this->createSelfServicePortalFacadeWithSetter($sspAssetQuoteItemSetterMock);

        // Act
        $quoteResponseTransfer = $facade->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($expectedQuoteResponseTransfer, $quoteResponseTransfer);
    }

    public function testAttachSspAssetToQuoteItemWithNullAssetReference(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $sspAssetQuoteItemAttachmentRequestTransfer->setSspAssetReference(null);
        $expectedQuoteResponseTransfer = $this->createSuccessfulQuoteResponseTransfer();

        $sspAssetQuoteItemSetterMock = $this->createSspAssetQuoteItemSetterMock();
        $sspAssetQuoteItemSetterMock->method('setSspAssetToQuoteItem')
            ->with($sspAssetQuoteItemAttachmentRequestTransfer)
            ->willReturn($expectedQuoteResponseTransfer);

        $facade = $this->createSelfServicePortalFacadeWithSetter($sspAssetQuoteItemSetterMock);

        // Act
        $quoteResponseTransfer = $facade->attachSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertSame($expectedQuoteResponseTransfer, $quoteResponseTransfer);
    }

    public function testSetSspAssetToQuoteItemSuccess(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $quoteFacadeMock = $this->createQuoteFacadeMock();
        $quoteItemFinderPluginMock = $this->createQuoteItemFinderPluginMock();

        $sspAssetQuoteItemSetter = new SspAssetQuoteItemSetter($quoteFacadeMock, $quoteItemFinderPluginMock);

        // Act
        $quoteResponseTransfer = $sspAssetQuoteItemSetter->setSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotNull($quoteResponseTransfer->getQuoteTransfer());

        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $this->assertEquals($this->quoteTransfer->getIdQuote(), $quoteTransfer->getIdQuote());
        $this->assertNotNull($quoteTransfer->getCustomer());
        $this->assertEquals($this->quoteTransfer->getCustomer()->getCustomerReference(), $quoteTransfer->getCustomer()->getCustomerReference());
    }

    public function testSetSspAssetToQuoteItemQuoteNotFound(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $sspAssetQuoteItemAttachmentRequestTransfer->setIdQuote(999);

        $quoteFacadeMock = $this->createMock(QuoteFacadeInterface::class);
        $quoteFacadeMock->method('findQuoteById')
            ->with(999)
            ->willReturn((new QuoteResponseTransfer())->setIsSuccessful(false));

        $quoteItemFinderPluginMock = $this->createQuoteItemFinderPluginMock();

        $sspAssetQuoteItemSetter = new SspAssetQuoteItemSetter($quoteFacadeMock, $quoteItemFinderPluginMock);

        // Act
        $quoteResponseTransfer = $sspAssetQuoteItemSetter->setSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    public function testSetSspAssetToQuoteItemItemNotFound(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $quoteFacadeMock = $this->createQuoteFacadeMock();

        $quoteItemFinderPluginMock = $this->createMock(QuoteItemFinderInterface::class);
        $quoteItemFinderPluginMock->method('findItem')->willReturn(null);

        $sspAssetQuoteItemSetter = new SspAssetQuoteItemSetter($quoteFacadeMock, $quoteItemFinderPluginMock);

        // Act
        $quoteResponseTransfer = $sspAssetQuoteItemSetter->setSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
    }

    public function testSetSspAssetToQuoteItemRemoveAsset(): void
    {
        // Arrange
        $sspAssetQuoteItemAttachmentRequestTransfer = $this->createSspAssetQuoteItemAttachmentRequestTransfer();
        $sspAssetQuoteItemAttachmentRequestTransfer->setSspAssetReference(null);

        $quoteFacadeMock = $this->createQuoteFacadeMock();
        $quoteItemFinderPluginMock = $this->createQuoteItemFinderPluginMock();

        $sspAssetQuoteItemSetter = new SspAssetQuoteItemSetter($quoteFacadeMock, $quoteItemFinderPluginMock);

        // Act
        $quoteResponseTransfer = $sspAssetQuoteItemSetter->setSspAssetToQuoteItem($sspAssetQuoteItemAttachmentRequestTransfer);

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
    }

    protected function createSspAssetQuoteItemAttachmentRequestTransfer(): SspAssetQuoteItemAttachmentRequestTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_GROUP_KEY);

        return (new SspAssetQuoteItemAttachmentRequestTransfer())
            ->setIdQuote($this->quoteTransfer->getIdQuote())
            ->setSspAssetReference(static::TEST_SSP_ASSET_REFERENCE)
            ->setItem($itemTransfer);
    }

    protected function createSuccessfulQuoteResponseTransfer(): QuoteResponseTransfer
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_GROUP_KEY)
            ->setSspAsset(
                (new SspAssetTransfer())->setReference(static::TEST_SSP_ASSET_REFERENCE),
            );

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference(static::TEST_CUSTOMER_REFERENCE);

        $quoteTransfer = (new QuoteTransfer())
            ->setIdQuote(static::TEST_QUOTE_ID)
            ->setCustomer($customerTransfer)
            ->setItems(new ArrayObject([$itemTransfer]));

        return (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($quoteTransfer);
    }

    protected function createFailedQuoteResponseTransfer(): QuoteResponseTransfer
    {
        return (new QuoteResponseTransfer())
            ->setIsSuccessful(false);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote\SspAssetQuoteItemSetterInterface
     */
    protected function createSspAssetQuoteItemSetterMock(): SspAssetQuoteItemSetterInterface
    {
        return $this->createMock(SspAssetQuoteItemSetterInterface::class);
    }

    protected function createSelfServicePortalFacadeWithSetter(SspAssetQuoteItemSetterInterface $sspAssetQuoteItemSetter): SelfServicePortalFacade
    {
        $factoryMock = $this->createMock(SelfServicePortalBusinessFactory::class);
        $factoryMock->method('createSspAssetQuoteItemSetter')->willReturn($sspAssetQuoteItemSetter);

        $facade = new SelfServicePortalFacade();
        $facade->setFactory($factoryMock);

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    protected function createQuoteFacadeMock(): QuoteFacadeInterface
    {
        $quoteFacadeMock = $this->createMock(QuoteFacadeInterface::class);

        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteTransfer($this->quoteTransfer);

        $quoteFacadeMock->method('findQuoteById')
            ->with($this->quoteTransfer->getIdQuote())
            ->willReturn($quoteResponseTransfer);

        $quoteFacadeMock->method('updateQuote')
            ->willReturn($quoteResponseTransfer);

        return $quoteFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SelfServicePortal\Business\Asset\Quote\QuoteItemFinderInterface
     */
    protected function createQuoteItemFinderPluginMock(): QuoteItemFinderInterface
    {
        $quoteItemFinderPluginMock = $this->createMock(QuoteItemFinderInterface::class);

        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_GROUP_KEY);

        $quoteItemFinderPluginMock->method('findItem')
            ->willReturn($itemTransfer);

        return $quoteItemFinderPluginMock;
    }
}
