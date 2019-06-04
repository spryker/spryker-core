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
use Spryker\Shared\PersistentCartShare\PersistentCartShareConfig;

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
     * @uses \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
     */
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.reader.error.resource_is_not_found_by_provided_uuid';

    /**
     * @uses \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidator::GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED
     */
    protected const GLOSSARY_KEY_RESOURCE_SHARE_IS_EXPIRED = 'resource_share.validation.error.resource_share_is_expired';

    /**
     * @uses \Spryker\Zed\PersistentCartShare\Business\Reader\QuoteReader::GLOSSARY_KEY_QUOTE_IS_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE = 'persistent_cart_share.error.quote_is_not_available';

    /**
     * @uses \Spryker\Zed\PersistentCartShare\Business\Reader\QuoteReader::GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_AVAILABLE = 'persistent_cart_share.error.resource_is_not_available';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     */
    public const SHARE_OPTION_FULL_ACCESS = 'FULL_ACCESS';

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

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getPreviewQuoteResourceShare((new ResourceShareRequestTransfer())->setResourceShare($resourceShareTransferForRequest));

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
            ->getPreviewQuoteResourceShare(
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
            ->getPreviewQuoteResourceShare(
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
            ResourceShareDataTransfer::ID_QUOTE => static::VALUE_NOT_EXISTING_ID_QUOTE,
        ]);

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getPreviewQuoteResourceShare(
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
            ResourceShareDataTransfer::SHARE_OPTION => static::SHARE_OPTION_FULL_ACCESS,
        ]);

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getPreviewQuoteResourceShare(
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

        $resourceShareDataTransfer = (new ResourceShareDataTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setShareOption(PersistentCartShareConfig::SHARE_OPTION_KEY_PREVIEW)
            ->fromArray($resourceShareDataSeedData);

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
