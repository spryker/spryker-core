<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Storage;

use ArrayObject;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface;
use Spryker\Client\MultiCart\MultiCartConfig;

class MultiCartStorage implements MultiCartStorageInterface
{
    /**
     * @var string
     */
    public const SESSION_KEY_QUOTE_COLLECTION = 'SESSION_KEY_QUOTE_COLLECTION';

    /**
     * @var \Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface
     */
    protected MultiCartToSessionClientInterface $sessionClient;

    /**
     * @var \Spryker\Client\MultiCart\MultiCartConfig
     */
    protected MultiCartConfig $multiCartConfig;

    /**
     * @param \Spryker\Client\MultiCart\Dependency\Client\MultiCartToSessionClientInterface $sessionClient
     * @param \Spryker\Client\MultiCart\MultiCartConfig $multiCartConfig
     */
    public function __construct(MultiCartToSessionClientInterface $sessionClient, MultiCartConfig $multiCartConfig)
    {
        $this->sessionClient = $sessionClient;
        $this->multiCartConfig = $multiCartConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return void
     */
    public function setQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer): void
    {
        $quoteCollectionTransfer = $this->filterQuoteCollectionByAllowedInSessionQuoteFieldsConfiguration($quoteCollectionTransfer);

        $this->sessionClient->set(static::SESSION_KEY_QUOTE_COLLECTION, $quoteCollectionTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        return $this->sessionClient->get(static::SESSION_KEY_QUOTE_COLLECTION, new QuoteCollectionTransfer());
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        $quoteCollection = $this->getQuoteCollection();
        foreach ($quoteCollection->getQuotes() as $quoteTransfer) {
            if ($quoteTransfer->getIdQuote() === $idQuote) {
                return $quoteTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function filterQuoteCollectionByAllowedInSessionQuoteFieldsConfiguration(
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteCollectionTransfer {
        $allowedQuoteFields = $this->multiCartConfig->getQuoteFieldsAllowedForCustomerQuoteCollectionInSession();
        if ($allowedQuoteFields === []) {
            return $quoteCollectionTransfer;
        }

        $filteredQuoteTransfers = new ArrayObject();
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $quoteData = $this->filterOutNotAllowedQuoteFields($quoteTransfer->toArray(true, true), $allowedQuoteFields);
            $filteredQuoteTransfers->append((new QuoteTransfer())->fromArray($quoteData, true));
        }

        return $quoteCollectionTransfer->setQuotes($filteredQuoteTransfers);
    }

    /**
     * @param array<string, mixed> $quoteData
     * @param array<string|array<string>> $allowedQuoteFields
     *
     * @return array<string, mixed>
     */
    protected function filterOutNotAllowedQuoteFields(array $quoteData, array $allowedQuoteFields): array
    {
        $filteredQuoteData = [];
        foreach ($allowedQuoteFields as $key => $allowedQuoteField) {
            if (is_string($allowedQuoteField) && isset($quoteData[$allowedQuoteField])) {
                $filteredQuoteData[$allowedQuoteField] = $quoteData[$allowedQuoteField];

                continue;
            }

            if (is_array($allowedQuoteField) && isset($quoteData[$key])) {
                foreach ($quoteData[$key] as $quoteFieldData) {
                    $filteredQuoteData[$key][] = $this->filterOutNotAllowedQuoteFields($quoteFieldData, $allowedQuoteField);
                }
            }
        }

        return $filteredQuoteData;
    }
}
