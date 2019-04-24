<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCartShare\Business;

use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\DataBuilder\ResourceShareBuilder;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShare;
use Spryker\Zed\PersistentCartShare\Communication\Plugin\PersistentCartShareResourceDataExpanderStrategyPlugin;
use Spryker\Zed\ResourceShare\ResourceShareDependencyProvider;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group PersistentCartShare
 * @group Business
 * @group Facade
 * @group PersistentCartShareFacadeTest
 * Add your own group annotations below this line
 */
class PersistentCartShareFacadeTest extends Unit
{
    protected const RESOURCE_TYPE_QUOTE = "quote";
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';
    protected const SHARE_OPTION_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
     */
    protected const RESOURCE_SHARE_READER_ERROR_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.reader.error.resource_is_not_found_by_provided_uuid';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidator::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
     */
    protected const GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED = 'resource_share.validation.error.resource_share_is_expired';

    /**
     * @see \Spryker\Zed\PersistentCartShare\Business\Model\QuoteForPreviewReader::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE
     */
    protected const PERSISTENT_CART_ERROR_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

    /**
     * @see \Spryker\Zed\PersistentCartShare\Business\Model\QuoteForPreviewReader::GLOSSARY_KEY_PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR
     */
    protected const PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR = 'persistent_cart_share.quote.access_denied.error';
    protected const SHARE_OPTION_PARAMETER = 'share_option';
    protected const ID_QUOTE_PARAMETER = 'id_quote';

    /**
     * @var \SprykerTest\Zed\PersistentCartShare\PersistentCartShareBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsQuoteResponseTransferWhenQuotePersistsAndSharedForPreview(): void
    {
        // Arrange
        $resourceShareTransfer = $this->createPreviewResourceShare();

        $resourceShareTransferForRequest = (new ResourceShareTransfer())
            ->setUuid($resourceShareTransfer->getUuid());

        $this->registerResourceShareResourceDataExpanderStrategyPlugin(new PersistentCartShareResourceDataExpanderStrategyPlugin());

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview((new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransferForRequest));

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteResponseTransfer->getErrors());
        $this->assertInstanceOf(QuoteTransfer::class, $quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsErrorsWhenQuoteIsNotShared(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setUuid('not-existing-uuid-or-not-a-uuid-at-all');

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview((new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer));

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        $this->assertContainsOnlyInstancesOf(QuoteErrorTransfer::class, $quoteResponseTransfer->getErrors());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::RESOURCE_SHARE_READER_ERROR_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsErrorsWhenQuoteSharedButExpired(): void
    {
        // Arrange
        $resourceShareTransferForRequest = $this->createExpiredResourceShare();

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview(
                (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransferForRequest)
            );

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        $this->assertContainsOnlyInstancesOf(QuoteErrorTransfer::class, $quoteResponseTransfer->getErrors());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    protected function createExpiredResourceShare(): ResourceShareTransfer
    {
        $resourceShareTransfer = (new ResourceShareBuilder([
            ResourceShareTransfer::EXPIRY_DATE => (new DateTime('Today last year'))->format('Y-m-d'),
        ]))->build();

        $resourceShareEntity = new SpyResourceShare();
        $resourceShareEntity->fromArray($resourceShareTransfer->toArray());

        $resourceShareEntity->save();

        return (new ResourceShareTransfer())->fromArray($resourceShareEntity->toArray(), true);
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsErrorsWhenShareExistsButThereIsNoQuoteWithSuchId(): void
    {
        // Arrange
        $resourceShareTransferForRequest = $this->createPreviewResourceShare([], [
            static::ID_QUOTE_PARAMETER => 99999999,
        ]);

        $this->registerResourceShareResourceDataExpanderStrategyPlugin(new PersistentCartShareResourceDataExpanderStrategyPlugin());

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview(
                (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransferForRequest)
            );

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        $this->assertContainsOnlyInstancesOf(QuoteErrorTransfer::class, $quoteResponseTransfer->getErrors());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::PERSISTENT_CART_ERROR_QUOTE_NOT_AVAILABLE, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsErrorsWhenShareExistsButShareOptionIsNotForPreview(): void
    {
        // Arrange
        $resourceShareTransferForRequest = $this->createPreviewResourceShare([], [
            static::SHARE_OPTION_PARAMETER => static::SHARE_OPTION_FULL_ACCESS,
        ]);

        $this->registerResourceShareResourceDataExpanderStrategyPlugin(new PersistentCartShareResourceDataExpanderStrategyPlugin());

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview(
                (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransferForRequest)
            );

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        $this->assertContainsOnlyInstancesOf(QuoteErrorTransfer::class, $quoteResponseTransfer->getErrors());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }

    /**
     * @param array $resourceShareSeedData
     * @param array $resourceShareDataSeedData
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    protected function createPreviewResourceShare(array $resourceShareSeedData = [], array $resourceShareDataSeedData = []): ResourceShareTransfer
    {
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $resourceShareDataDefaults = [
            static::ID_QUOTE_PARAMETER => $quoteTransfer->getIdQuote(),
            static::SHARE_OPTION_PARAMETER => static::SHARE_OPTION_PREVIEW,
        ];

        $resourceShareDataTransfer = (new ResourceShareDataTransfer())->setData(
            array_merge($resourceShareDataDefaults, $resourceShareDataSeedData)
        );

        $resourceShareDefaults = [
            ResourceShareTransfer::CUSTOMER_REFERENCE => $quoteTransfer->getCustomerReference(),
            ResourceShareTransfer::RESOURCE_TYPE => static::RESOURCE_TYPE_QUOTE,
            ResourceShareTransfer::RESOURCE_SHARE_DATA => $resourceShareDataTransfer,
            ResourceShareTransfer::EXPIRY_DATE => (new DateTime('+1 day'))->format('Y-m-d'),
        ];

        $resourceShareTransfer = $this->tester->haveResourceShare(
            array_merge($resourceShareDefaults, $resourceShareSeedData)
        );

        return $resourceShareTransfer;
    }

    /**
     * @param \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface $resourceShareResourceDataExpanderStrategyPlugin
     *
     * @return void
     */
    protected function registerResourceShareResourceDataExpanderStrategyPlugin(
        ResourceShareResourceDataExpanderStrategyPluginInterface $resourceShareResourceDataExpanderStrategyPlugin
    ): void {
        $this->tester->setDependency(ResourceShareDependencyProvider::PLUGINS_RESOURCE_SHARE_RESOURCE_DATA_EXPANDER_STRATEGY, [
            $resourceShareResourceDataExpanderStrategyPlugin,
        ]);
    }
}
