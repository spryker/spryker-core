<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PersistentCartShare\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Service\UtilEncoding\UtilEncodingService;

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
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND
     */
    protected const RESOURCE_SHARE_READER_ERROR_RESOURCE_IS_NOT_FOUND = 'resource_share.reader.error.resource_is_not_found';

    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_HAS_EXPIRED
     */
    protected const RESOURCE_SHARE_READER_ERROR_RESOURCE_IS_HAS_EXPIRED = 'resource_share.reader.error.resource_has_expired';

    /**
     * @see \Spryker\Zed\PersistentCartShare\Business\Model\QuoteForPreviewReader::GLOSSARY_KEY_ERROR_QUOTE_NOT_AVAILABLE
     */
    protected const PERSISTENT_CART_ERROR_QUOTE_NOT_AVAILABLE = 'persistent_cart.error.quote.not_available';

    /**
     * @see \Spryker\Zed\PersistentCartShare\Business\Model\QuoteForPreviewReader::GLOSSARY_KEY_PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR
     */
    protected const PERSISTENT_CART_SHARE_QUOTE_ACCESS_DENIED_ERROR = 'persistent_cart_share.quote.access_denied.error';

    /**
     * @var \SprykerTest\Zed\PersistentCartShare\PersistentCartShareBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsQuoteResponseTransferWhenQuotePersistsAndSharedForPreview(): void
    {
        // Arrange;
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $utilEncodingService = new UtilEncodingService();
        $resourceData = $utilEncodingService->encodeJson([
            'id_quote' => $quoteTransfer->getIdQuote(),
            'share_option' => static::SHARE_OPTION_PREVIEW,
        ]);

        $resourceShareTransfer = $this->tester->haveResourceShare([
            'customerReference' => $quoteTransfer->getCustomerReference(),
            "resourceType" => static::RESOURCE_TYPE_QUOTE,
            "resourceData" => $resourceData,
            "expiryDate" => strtotime('+1 day'),
        ]);

        // Act
        $quoteResponseTransfer = $this->getFacade()->getQuoteForPreview((new ResourceShareTransfer())
            ->setUuid($resourceShareTransfer->getUuid()));

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
        // Arrange;
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setUuid('not-existing-uuid-or-not-a-uuid-at-all');

        // Act
        $quoteResponseTransfer = $this->getFacade()->getQuoteForPreview($resourceShareTransfer);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        $this->assertContainsOnlyInstancesOf(QuoteErrorTransfer::class, $quoteResponseTransfer->getErrors());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::RESOURCE_SHARE_READER_ERROR_RESOURCE_IS_NOT_FOUND, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsErrorsWhenQuoteSharedButExpired(): void
    {
        $this->markTestSkipped(
            'This test will be updated when validate for expire date will be implemented.'
        );

        // Arrange;
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $utilEncodingService = new UtilEncodingService();
        $resourceData = $utilEncodingService->encodeJson([
            'id_quote' => $quoteTransfer->getIdQuote(),
            'share_option' => static::SHARE_OPTION_PREVIEW,
        ]);

        $resourceShareTransfer = $this->tester->haveResourceShare([
            'customerReference' => $quoteTransfer->getCustomerReference(),
            "resourceType" => static::RESOURCE_TYPE_QUOTE,
            "resourceData" => $resourceData,
            "expiryDate" => strtotime('-1 day'),
        ]);

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview((new ResourceShareTransfer())
                ->setUuid($resourceShareTransfer->getUuid()));

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        $this->assertContainsOnlyInstancesOf(QuoteErrorTransfer::class, $quoteResponseTransfer->getErrors());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::RESOURCE_SHARE_READER_ERROR_RESOURCE_IS_HAS_EXPIRED, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsErrorsWhenShareExistsButThereIsNoQuoteWithSuchId(): void
    {
        // Arrange;
        $utilEncodingService = new UtilEncodingService();
        $resourceData = $utilEncodingService->encodeJson([
            'id_quote' => 99999999,
            'share_option' => static::SHARE_OPTION_PREVIEW,
        ]);

        $resourceShareTransfer = $this->tester->haveResourceShare([
            "resourceType" => static::RESOURCE_TYPE_QUOTE,
            "resourceData" => $resourceData,
            "expiryDate" => strtotime('+1 day'),
        ]);

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview((new ResourceShareTransfer())
                ->setUuid($resourceShareTransfer->getUuid()));

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
        // Arrange;
        $customerTransfer = $this->tester->haveCustomer();
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $utilEncodingService = new UtilEncodingService();
        $resourceData = $utilEncodingService->encodeJson([
            'id_quote' => $quoteTransfer->getIdQuote(),
            'share_option' => static::SHARE_OPTION_FULL_ACCESS,
        ]);

        $resourceShareTransfer = $this->tester->haveResourceShare([
            "resourceType" => static::RESOURCE_TYPE_QUOTE,
            "resourceData" => $resourceData,
            "expiryDate" => strtotime('+1 day'),
        ]);

        // Act
        $quoteResponseTransfer = $this->getFacade()
            ->getQuoteForPreview((new ResourceShareTransfer())
                ->setUuid($resourceShareTransfer->getUuid()));

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
}
