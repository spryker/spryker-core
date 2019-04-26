<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCartShare;

use Codeception\Test\Unit;
use Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group PersistentCartShare
 * @group PersistentCartShareClientTest
 * Add your own group annotations below this line
 */
class PersistentCartShareClientTest extends Unit
{
    /**
     * @see \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID
     */
    protected const GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID = 'resource_share.reader.error.resource_is_not_found_by_provided_uuid';
    protected const NOT_EXISTING_UUID_OR_NOT_A_UUID_AT_ALL = 'not-existing-uuid-or-not-a-uuid-at-all';

    /**
     * @var \SprykerTest\Client\PersistentCartShare\PersistentCartShareClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuoteForPreviewReturnsQuoteResponseTransferByZedRequest(): void
    {
        // Arrange
        $resourceShareUuid = static::NOT_EXISTING_UUID_OR_NOT_A_UUID_AT_ALL;

        // Act
        $quoteResponseTransfer = $this->getPersistentCartShareClient()->getQuoteForPreview($resourceShareUuid);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotEmpty($quoteResponseTransfer->getErrors());
        $this->assertEmpty($quoteResponseTransfer->getQuoteTransfer());
        /** @var \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer */
        $errors = $quoteResponseTransfer->getErrors();
        $quoteErrorTransfer = reset($errors);
        $this->assertEquals(static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID, $quoteErrorTransfer->getMessage());
    }

    /**
     * @return \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface
     */
    protected function getPersistentCartShareClient(): PersistentCartShareClientInterface
    {
        return $this->tester->getLocator()->persistentCartShare()->client();
    }
}
