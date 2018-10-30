<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\GuestQuote;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface;
use Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface;
use Spryker\Zed\Quote\QuoteConfig;

class GuestQuoteDeleter implements GuestQuoteDeleterInterface
{
    use TransactionTrait;

    protected const BATCH_SIZE_LIMIT = 200;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface
     */
    protected $quoteEntityManager;

    /**
     * @var \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteDeleteBeforePlugins;

    /**
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $quoteEntityManager
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteDeleteBeforePlugins
     * @param \Spryker\Zed\Quote\QuoteConfig $config
     */
    public function __construct(
        QuoteEntityManagerInterface $quoteEntityManager,
        QuoteRepositoryInterface $quoteRepository,
        array $quoteDeleteBeforePlugins,
        QuoteConfig $config
    ) {
        $this->quoteEntityManager = $quoteEntityManager;
        $this->quoteRepository = $quoteRepository;
        $this->quoteDeleteBeforePlugins = $quoteDeleteBeforePlugins;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function deleteExpiredGuestQuote(): void
    {
        $quoteCollectionTransfer = $this->findExpiredGuestQuotes();

        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
                return $this->executeDeleteTransaction($quoteTransfer);
            });
        }
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findExpiredGuestQuotes(): QuoteCollectionTransfer
    {
        $lifetime = $this->config->getGuestQuoteLifetime();
        $lifetimeInterval = new DateInterval($lifetime);
        $lifetimeLimitDate = (new DateTime())->sub($lifetimeInterval);

        $quoteCriteriaFilterTransfer = new QuoteCriteriaFilterTransfer();
        $quoteCriteriaFilterTransfer->setFilter(
            (new FilterTransfer())->setLimit(static::BATCH_SIZE_LIMIT)
        );

        return $this->quoteRepository->findExpiredGuestQuotes($lifetimeLimitDate, $quoteCriteriaFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function validateQuote(QuoteTransfer $quoteTransfer): bool
    {
        $loadedQuoteTransfer = $this->quoteRepository->findQuoteById($quoteTransfer->getIdQuote());
        if (!$loadedQuoteTransfer) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeDeleteTransaction(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = new QuoteResponseTransfer();
        $quoteTransfer = $this->executeDeleteBeforePlugins($quoteTransfer);
        $this->quoteEntityManager->deleteQuoteById($quoteTransfer->getIdQuote());
        $quoteResponseTransfer->setIsSuccessful(true);

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeDeleteBeforePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quoteDeleteBeforePlugins as $quoteWritePlugin) {
            $quoteTransfer = $quoteWritePlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
