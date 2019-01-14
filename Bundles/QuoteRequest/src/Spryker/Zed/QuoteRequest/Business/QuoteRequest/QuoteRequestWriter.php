<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestWriter implements QuoteRequestWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface
     */
    protected $referenceGenerator;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $config
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $entityManager
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $repository
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface $referenceGenerator
     */
    public function __construct(
        QuoteRequestConfig $config,
        QuoteRequestEntityManagerInterface $entityManager,
        QuoteRequestRepositoryInterface $repository,
        QuoteRequestToCustomerFacadeInterface $customerFacade,
        QuoteRequestReferenceGeneratorInterface $referenceGenerator
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->customerFacade = $customerFacade;
        $this->referenceGenerator = $referenceGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function createQuoteRequestFromQuote(QuoteTransfer $quoteTransfer): QuoteRequestTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($quoteTransfer) {
                return $this->executeCreateQuoteRequestFromQuoteTransaction($quoteTransfer);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function executeCreateQuoteRequestFromQuoteTransaction(QuoteTransfer $quoteTransfer): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->createQuoteRequestTransfer($quoteTransfer);
        $quoteRequestVersionTransfer = $this->createQuoteRequestVersionTransfer($quoteTransfer);

        $quoteRequestTransfer->setLatestVersionStatus($quoteRequestVersionTransfer->getStatus());
        $quoteRequestTransfer->setQuoteRequestReference(
            $this->referenceGenerator->generateQuoteRequestReference($quoteRequestTransfer)
        );

        $quoteRequestTransfer = $this->entityManager->saveQuoteRequest($quoteRequestTransfer);

        $quoteRequestVersionTransfer->setFkQuoteRequest($quoteRequestTransfer->getIdQuoteRequest());
        $quoteRequestVersionTransfer = $this->entityManager->saveQuoteRequestVersion($quoteRequestVersionTransfer);

        $quoteRequestTransfer->addQuoteRequestVersion($quoteRequestVersionTransfer);

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(QuoteTransfer $quoteTransfer): QuoteRequestTransfer
    {
        $customerTransfer = $quoteTransfer->getCustomer();

        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            // TODO: is feature only for b2b?
        }

        $quoteRequestTransfer = (new QuoteRequestTransfer())
            ->setIsCanceled(false)
            ->setCompanyUser($customerTransfer->getCompanyUserTransfer());

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function createQuoteRequestVersionTransfer(
        QuoteTransfer $quoteTransfer
    ): QuoteRequestVersionTransfer {
        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setOriginalQuote($quoteTransfer)
            ->setQuote($quoteTransfer)
            ->setVersion($this->config->getInitialVersion())
            ->setStatus($this->config->getInitialStatus());

        return $quoteRequestVersionTransfer;
    }
}
