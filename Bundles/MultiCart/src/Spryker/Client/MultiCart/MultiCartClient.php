<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Client\MultiCart\MultiCartFactory getFactory()
 */
class MultiCartClient extends AbstractClient implements MultiCartClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getDefaultCart(): QuoteTransfer
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->getQuote();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartUpdater()
            ->setDefaultQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollection(): QuoteCollectionTransfer
    {
        return $this->getFactory()
            ->createMultiCartStorage()
            ->getQuoteCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return void
     */
    public function setQuoteCollection(QuoteCollectionTransfer $quoteCollectionTransfer): void
    {
        $this->getFactory()->createMultiCartStorage()->setQuoteCollection($quoteCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    public function findQuoteById(int $idQuote): ?QuoteTransfer
    {
        return $this->getFactory()
            ->createMultiCartStorage()
            ->findQuoteById($idQuote);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isMultiCartAllowed(): bool
    {
        $storageStrategy = $this->getFactory()
            ->getQuoteClient()
            ->getStorageStrategy();

        return $storageStrategy === QuoteConfig::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartCreator()
            ->createQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartUpdater()
            ->updateQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->setCustomer(
            $this->getFactory()->getCustomerClient()->getCustomer()
        );

        return $this->getFactory()
            ->getPersistentCartClient()
            ->deleteQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function duplicateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartCreator()
            ->duplicateQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function clearQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartUpdater()
            ->clearQuote($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    public function getQuoteCollectionByCriteria(QuoteCriteriaFilterTransfer $quoteCriteriaFilterTransfer): QuoteCollectionTransfer
    {
        return $this->getFactory()->createCartReader()->getQuoteCollectionByCriteria($quoteCriteriaFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isQuoteDeletable(): bool
    {
        return $this->getFactory()
            ->createCartDeleteChecker()
            ->isQuoteDeletable();
    }
}
