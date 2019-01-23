<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
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
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface
     */
    protected $referenceGenerator;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $config
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $entityManager
     * @param \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface $referenceGenerator
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface $companyUserFacade
     */
    public function __construct(
        QuoteRequestConfig $config,
        QuoteRequestEntityManagerInterface $entityManager,
        QuoteRequestReferenceGeneratorInterface $referenceGenerator,
        QuoteRequestToCompanyUserInterface $companyUserFacade
    ) {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->referenceGenerator = $referenceGenerator;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function create(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestTransfer) {
            return $this->executeCreateTransaction($quoteRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancel(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        // TODO: Implement cancel() method.
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeCreateTransaction(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer = $this->createQuoteRequest($quoteRequestTransfer);
        $quoteRequestVersionTransfer = $this->createQuoteRequestVersion($quoteRequestTransfer);

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccess(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        $quoteRequestTransfer->requireCompanyUser()
            ->getCompanyUser()
            ->requireIdCompanyUser();

        $customerReference = $this->getCustomerReference($quoteRequestTransfer->getCompanyUser());

        $quoteRequestTransfer->setStatus($this->config->getInitialStatus());
        $quoteRequestTransfer->setQuoteRequestReference(
            $this->referenceGenerator->generateQuoteRequestReference($quoteRequestTransfer, $customerReference)
        );

        return $this->entityManager->saveQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function createQuoteRequestVersion(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->requireQuote();

        $quoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersion()
            ->setVersion($this->config->getInitialVersion())
            ->setFkQuoteRequest($quoteRequestTransfer->getIdQuoteRequest());

        $quoteRequestVersionTransfer->setVersionReference(
            $this->referenceGenerator->generateQuoteRequestVersionReference($quoteRequestTransfer, $quoteRequestVersionTransfer)
        );

        return $this->entityManager->saveQuoteRequestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return string
     */
    protected function getCustomerReference(CompanyUserTransfer $companyUserTransfer): string
    {
        $customerReferences = $this->companyUserFacade
            ->getCustomerReferencesByCompanyUserIds([$companyUserTransfer->getIdCompanyUser()]);

        return array_shift($customerReferences);
    }
}
