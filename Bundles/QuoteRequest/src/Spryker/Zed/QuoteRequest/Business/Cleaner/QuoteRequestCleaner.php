<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Cleaner;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestCleaner implements QuoteRequestCleanerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     */
    public function __construct(
        QuoteRequestRepositoryInterface $quoteRequestRepository,
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager
    ) {
        $this->quoteRequestRepository = $quoteRequestRepository;
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return void
     */
    public function deleteQuoteRequestsByIdCompanyUser(int $idCompanyUser): void
    {
        $quoteRequestIds = $this->quoteRequestRepository->findQuoteRequestIdsByIdCompanyUser($idCompanyUser);

        if (!$quoteRequestIds) {
            return;
        }

        $this->deleteQuoteRequestsByIds($quoteRequestIds);
    }

    /**
     * @param int[] $quoteRequestIds
     *
     * @return void
     */
    protected function deleteQuoteRequestsByIds(array $quoteRequestIds): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestIds): void {
            $this->executeDeleteQuoteRequestsByIdsTransaction($quoteRequestIds);
        });
    }

    /**
     * @param int[] $quoteRequestIds
     *
     * @return void
     */
    protected function executeDeleteQuoteRequestsByIdsTransaction(array $quoteRequestIds): void
    {
        $this->quoteRequestEntityManager->deleteQuoteRequestVersionsByQuoteRequestIds($quoteRequestIds);
        $this->quoteRequestEntityManager->deleteQuoteRequestsByIds($quoteRequestIds);
    }
}
