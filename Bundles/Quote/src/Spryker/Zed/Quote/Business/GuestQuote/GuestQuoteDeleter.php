<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\GuestQuote;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
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
     * @var \Spryker\Zed\Quote\QuoteConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected $quoteDeleteBeforePlugins;

    /**
     * @param \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface $quoteEntityManager
     * @param \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface $quoteRepository
     * @param \Spryker\Zed\Quote\QuoteConfig $config
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[] $quoteDeleteBeforePlugins
     */
    public function __construct(
        QuoteEntityManagerInterface $quoteEntityManager,
        QuoteRepositoryInterface $quoteRepository,
        QuoteConfig $config,
        array $quoteDeleteBeforePlugins
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
        do {
            $quoteCollectionTransfer = $this->findExpiredGuestQuotes();

            foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
                $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer) {
                    $this->executeDeleteTransaction($quoteTransfer);
                });
            }
        } while ($quoteCollectionTransfer->getQuotes()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteCollectionTransfer
     */
    protected function findExpiredGuestQuotes(): QuoteCollectionTransfer
    {
        $lifetime = $this->config->getGuestQuoteLifetime();
        $lifetimeInterval = new DateInterval($lifetime);
        $lifetimeLimitDate = (new DateTime())->sub($lifetimeInterval);

        return $this->quoteRepository->findExpiredGuestQuotes($lifetimeLimitDate, static::BATCH_SIZE_LIMIT);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function executeDeleteTransaction(QuoteTransfer $quoteTransfer): void
    {
        $quoteTransfer = $this->executeDeleteBeforePlugins($quoteTransfer);
        $this->quoteEntityManager->deleteQuoteById($quoteTransfer->getIdQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function executeDeleteBeforePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($this->quoteDeleteBeforePlugins as $quoteDeleteBeforePlugin) {
            $quoteTransfer = $quoteDeleteBeforePlugin->execute($quoteTransfer);
        }

        return $quoteTransfer;
    }
}
