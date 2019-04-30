<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCartShare\Business;

use Codeception\TestCase\Test;
use DateTime;
use Generated\Shared\DataBuilder\ResourceShareBuilder;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareDataTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Orm\Zed\ResourceShare\Persistence\SpyResourceShare;
use Spryker\Zed\PersistentCartShare\Communication\Plugin\PersistentCartShareResourceDataExpanderStrategyPlugin;
use Spryker\Zed\PersistentCartShare\PersistentCartShareConfig;
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
class PersistentCartShareFacadeTest extends Test
{
    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
     */
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.reader.error.resource_is_not_found_by_provided_uuid';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidator::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
     */
    protected const GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED = 'resource_share.validation.error.resource_share_is_expired';

    /**
     * @see \Spryker\Zed\PersistentCartShare\Business\Quote\QuoteReader::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

    /**
     * @see \Spryker\Zed\PersistentCartShare\Business\Quote\QuoteReader::GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.resource_is_not_available';

    protected const VALUE_NOT_EXISTING_UUID = 'VALUE_NOT_EXISTING_UUID';
    protected const VALUE_NOT_EXISTING_ID_QUOTE = 0;

    /**
     * @var \SprykerTest\Zed\PersistentCartShare\PersistentCartShareBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnQuoteTransferWhenQuoteIsFoundAndSharedForPreview(): void
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
        $this->assertNotNull($quoteResponseTransfer->getQuoteTransfer());
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnErrorMessageWhenResourceShareIsNotFoundByUuid(): void
    {
        // Arrange
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setUuid(static::VALUE_NOT_EXISTING_UUID);

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview(
                (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
            );

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasErrorMessage(
            $quoteResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
        ));
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnErrorMessageWhenQuoteIsSharedButExpired(): void
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
        $this->assertTrue($this->hasErrorMessage(
            $quoteResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
        ));
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnErrorMessageWhenQuoteIdIsIncorrect(): void
    {
        // Arrange
        $resourceShareTransferForRequest = $this->createPreviewResourceShare([], [
            PersistentCartShareConfig::KEY_ID_QUOTE => static::VALUE_NOT_EXISTING_ID_QUOTE,
        ]);

        $this->registerResourceShareResourceDataExpanderStrategyPlugin(new PersistentCartShareResourceDataExpanderStrategyPlugin());

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview(
                (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransferForRequest)
            );

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasErrorMessage(
            $quoteResponseTransfer,
            static::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE
        ));
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnErrorMessageWhenQuoteIsSharedButNotForPreview(): void
    {
        // Arrange
        $resourceShareTransfer = $this->createPreviewResourceShare([], [
            PersistentCartShareConfig::KEY_SHARE_OPTION => PersistentCartShareConfig::SHARE_OPTION_FULL_ACCESS,
        ]);

        $this->registerResourceShareResourceDataExpanderStrategyPlugin(new PersistentCartShareResourceDataExpanderStrategyPlugin());

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview(
                (new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransfer)
            );

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasErrorMessage(
            $quoteResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE
        ));
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
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $resourceShareDataDefaults = [
            PersistentCartShareConfig::KEY_ID_QUOTE => $quoteTransfer->getIdQuote(),
            PersistentCartShareConfig::KEY_SHARE_OPTION => PersistentCartShareConfig::SHARE_OPTION_PREVIEW,
        ];

        $resourceShareDataTransfer = (new ResourceShareDataTransfer())->setData(
            array_merge($resourceShareDataDefaults, $resourceShareDataSeedData)
        );

        $resourceShareDefaults = [
            ResourceShareTransfer::CUSTOMER_REFERENCE => $quoteTransfer->getCustomerReference(),
            ResourceShareTransfer::RESOURCE_TYPE => PersistentCartShareConfig::RESOURCE_TYPE_QUOTE,
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
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param string $expectedErrorMessage
     *
     * @return bool
     */
    protected function hasErrorMessage(QuoteResponseTransfer $quoteResponseTransfer, string $expectedErrorMessage): bool
    {
        $quoteResponseTransfer->requireErrors();
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            if ($quoteErrorTransfer->getMessage() === $expectedErrorMessage) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade|\Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
