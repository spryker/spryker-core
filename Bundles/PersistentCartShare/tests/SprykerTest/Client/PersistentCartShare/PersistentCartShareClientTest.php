<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCartShare;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteResponseTransfer;
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

    protected const VALUE_NOT_EXISTING_UUID = 'VALUE_NOT_EXISTING_UUID';

    /**
     * @var \SprykerTest\Client\PersistentCartShare\PersistentCartShareClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnErrorMessageWhenUuidIsIncorrect(): void
    {
        // Act
        $quoteResponseTransfer = $this->getPersistentCartShareClient()
            ->getQuoteForPreview(static::VALUE_NOT_EXISTING_UUID);

        // Assert
        $this->assertFalse($quoteResponseTransfer->getIsSuccessful());
        $this->assertTrue($this->hasErrorMessage(
            $quoteResponseTransfer,
            static::GLOSSARY_KEY_RESOURCE_IS_NOT_FOUND_BY_PROVIDED_UUID)
        );
    }

    /**
     * @return void
     */
    public function testGetQuoteForPreviewShouldReturnQuoteWhenUuidIsCorrect(): void
    {
        // Arrange
        $resourceShareTransfer = $this->tester->haveResourceShare();

        // Act
        $quoteResponseTransfer = $this->getPersistentCartShareClient()
            ->getQuoteForPreview($resourceShareTransfer->getUuid());

        // Assert
        $this->assertTrue($quoteResponseTransfer->getIsSuccessful());
        $this->assertNotNull($quoteResponseTransfer->getQuoteTransfer());
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
     * @return \Spryker\Client\PersistentCartShare\PersistentCartShareClientInterface
     */
    protected function getPersistentCartShareClient(): PersistentCartShareClientInterface
    {
        return $this->tester->getLocator()->persistentCartShare()->client();
    }
}
