<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestWriter implements QuoteRequestWriterInterface
{
    use TransactionTrait;

    protected const GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND = 'quote_request.validation.error.company_user_not_found';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var \Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected $quoteRequestConfig;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface
     */
    protected $quoteRequestReferenceGenerator;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $quoteRequestConfig
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     * @param \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface $quoteRequestReferenceGenerator
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface $companyUserFacade
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface $calculationFacade
     */
    public function __construct(
        QuoteRequestConfig $quoteRequestConfig,
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager,
        QuoteRequestRepositoryInterface $quoteRequestRepository,
        QuoteRequestReferenceGeneratorInterface $quoteRequestReferenceGenerator,
        QuoteRequestToCompanyUserInterface $companyUserFacade,
        QuoteRequestToCalculationInterface $calculationFacade
    ) {
        $this->quoteRequestConfig = $quoteRequestConfig;
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
        $this->quoteRequestRepository = $quoteRequestRepository;
        $this->quoteRequestReferenceGenerator = $quoteRequestReferenceGenerator;
        $this->companyUserFacade = $companyUserFacade;
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestTransfer) {
            return $this->executeCreateQuoteRequestTransaction($quoteRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestTransfer) {
            return $this->executeUpdateQuoteRequestTransaction($quoteRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequestQuote(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestTransfer) {
            $quoteRequestTransfer->setLatestVersion(
                $this->addQuoteRequestVersion($quoteRequestTransfer)
            );

            $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

            return (new QuoteRequestResponseTransfer())
                ->setQuoteRequest($quoteRequestTransfer)
                ->setIsSuccessful(true);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function cancelQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestCriteriaTransfer
            ->requireQuoteRequestReference()
            ->requireIdCompanyUser();

        $quoteRequestTransfer = $this->findQuoteRequestTransfer($quoteRequestCriteriaTransfer);

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);
        }

        if (!$this->isQuoteRequestCancelable($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_CANCELED);
        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeCreateQuoteRequestTransaction(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer->requireCompanyUser()
            ->getCompanyUser()
            ->requireIdCompanyUser();

        $customerReference = $this->findCustomerReference($quoteRequestTransfer->getCompanyUser());

        if (!$customerReference) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND);
        }

        $quoteRequestTransfer = $this->createQuoteRequestTransfer($quoteRequestTransfer, $customerReference);

        $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->requireQuote()
            ->getQuote()
            ->requireItems();

        $quoteRequestTransfer->getLatestVersion()->setQuote(
            $this->calculationFacade->recalculate($quoteRequestTransfer->getLatestVersion()->getQuote())
        );

        $quoteRequestVersionTransfer = $this->createQuoteRequestVersionTransfer($quoteRequestTransfer);
        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeUpdateQuoteRequestTransaction(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer = $this->cleanUpQuoteInProgress($quoteRequestTransfer);
        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequestTransfer(QuoteRequestTransfer $quoteRequestTransfer, string $customerReference): QuoteRequestTransfer
    {
        $quoteRequestTransfer->setStatus($this->quoteRequestConfig->getInitialStatus());
        $quoteRequestTransfer->setQuoteRequestReference(
            $this->quoteRequestReferenceGenerator->generateQuoteRequestReference($quoteRequestTransfer, $customerReference)
        );

        return $this->quoteRequestEntityManager->createQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function createQuoteRequestVersionTransfer(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->requireQuote();

        $quoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersion()
            ->setVersion($this->quoteRequestConfig->getInitialVersion())
            ->setFkQuoteRequest($quoteRequestTransfer->getIdQuoteRequest());

        $quoteRequestVersionTransfer->setVersionReference(
            $this->quoteRequestReferenceGenerator->generateQuoteRequestVersionReference($quoteRequestTransfer, $quoteRequestVersionTransfer)
        );

        return $this->quoteRequestEntityManager->createQuoteRequestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function findQuoteRequestTransfer(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setWithHidden($quoteRequestCriteriaTransfer->getWithHidden())
            ->setQuoteRequestReference($quoteRequestCriteriaTransfer->getQuoteRequestReference())
            ->setCompanyUser((new CompanyUserTransfer())->setIdCompanyUser($quoteRequestCriteriaTransfer->getIdCompanyUser()));

        $quoteRequestTransfers = $this->quoteRequestRepository
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function cleanUpQuoteInProgress(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestTransfer
    {
        if (!$quoteRequestTransfer->getQuoteInProgress()) {
            return $quoteRequestTransfer;
        }

        $quoteRequestTransfer->getQuoteInProgress()
            ->setQuoteRequestReference(null)
            ->setQuoteRequestVersionReference(null);

        return $quoteRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return string|null
     */
    protected function findCustomerReference(CompanyUserTransfer $companyUserTransfer): ?string
    {
        $customerReferences = $this->companyUserFacade
            ->getCustomerReferencesByCompanyUserIds([$companyUserTransfer->getIdCompanyUser()]);

        return array_shift($customerReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestCancelable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return in_array($quoteRequestTransfer->getStatus(), $this->quoteRequestConfig->getCancelableStatuses());
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue($message));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function markQuoteRequestAsDraft(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestCriteriaTransfer->requireQuoteRequestReference();

        $quoteRequestTransfer = $this->findQuoteRequestTransfer($quoteRequestCriteriaTransfer);

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);
        }

        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_READY) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->requireQuote();

        $cleanQuoteTransfer = $this->clearSourcePrices($quoteRequestTransfer->getLatestVersion()->getQuote());
        $cleanQuoteTransfer = $this->calculationFacade->recalculate($cleanQuoteTransfer);

        $quoteRequestTransfer->setQuoteInProgress($cleanQuoteTransfer)
            ->setStatus(SharedQuoteRequestConfig::STATUS_DRAFT)
            ->setLatestVersion(
                $this->addQuoteRequestVersion($quoteRequestTransfer)
            );

        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function markQuoteRequestAsWaiting(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestCriteriaTransfer->requireQuoteRequestReference();

        $quoteRequestTransfer = $this->findQuoteRequestTransfer($quoteRequestCriteriaTransfer);

        if (!$quoteRequestTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS);
        }

        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_DRAFT) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestTransfer->requireLatestVersion()
            ->getLatestVersion()
            ->requireQuote();

        $quoteRequestTransfer->setStatus(SharedQuoteRequestConfig::STATUS_WAITING)
            ->setQuoteInProgress($quoteRequestTransfer->getLatestVersion()->getQuote());

        $quoteRequestTransfer->setLatestVersion($this->addQuoteRequestVersion($quoteRequestTransfer));

        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function addQuoteRequestVersion(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestTransfer->requireQuoteInProgress();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote($quoteRequestTransfer->getQuoteInProgress())
            ->setVersion($quoteRequestTransfer->getLatestVersion()->getVersion() + 1)
            ->setFkQuoteRequest($quoteRequestTransfer->getIdQuoteRequest());

        $quoteRequestVersionTransfer->setVersionReference(
            $this->quoteRequestReferenceGenerator->generateQuoteRequestVersionReference($quoteRequestTransfer, $quoteRequestVersionTransfer)
        );

        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);

        return $this->quoteRequestEntityManager->createQuoteRequestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function clearSourcePrices(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setSourceUnitGrossPrice(null)
                ->setSourceUnitNetPrice(null);
        }

        return $quoteTransfer;
    }
}
