<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Reader;

use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    protected const GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS = 'quote_request.validation.error.not_exists';

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $quoteRequestRepository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $quoteRequestRepository
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        QuoteRequestRepositoryInterface $quoteRequestRepository,
        QuoteRequestToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->quoteRequestRepository = $quoteRequestRepository;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function getQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestFilterTransfer->requireQuoteRequestReference();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->fromArray($quoteRequestFilterTransfer->toArray(), true);

        if ($quoteRequestFilterTransfer->getIdCompanyUser()) {
            $quoteRequestFilterTransfer->setCompanyUser(
                (new CompanyUserTransfer())->setIdCompanyUser($quoteRequestFilterTransfer->getIdCompanyUser())
            );
        }

        $quoteRequestTransfers = $this
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        $quoteRequestTransfer = array_shift($quoteRequestTransfers);

        if (!$quoteRequestTransfer) {
            return (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue(static::GLOSSARY_KEY_QUOTE_REQUEST_NOT_EXISTS));
        }

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(true)
            ->setQuoteRequest($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        $quoteRequestCollectionTransfer = $this->quoteRequestRepository
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        $quoteRequestCollectionTransfer = $this->expandQuoteRequestCollectionWithVersions($quoteRequestCollectionTransfer);
        $quoteRequestCollectionTransfer = $this->expandQuoteRequestCollectionWithBusinessUnits($quoteRequestCollectionTransfer);

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer
     */
    public function getQuoteRequestVersionCollectionByFilter(QuoteRequestVersionFilterTransfer $quoteRequestVersionFilterTransfer): QuoteRequestVersionCollectionTransfer
    {
        return $this->quoteRequestRepository
            ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return string|null
     */
    public function findCustomerReference(CompanyUserTransfer $companyUserTransfer): ?string
    {
        $customerReferences = $this->companyUserFacade
            ->getCustomerReferencesByCompanyUserIds([$companyUserTransfer->getIdCompanyUser()]);

        return array_shift($customerReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    protected function expandQuoteRequestCollectionWithVersions(
        QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
    ): QuoteRequestCollectionTransfer {
        foreach ($quoteRequestCollectionTransfer->getQuoteRequests() as $quoteRequestTransfer) {
            $quoteRequestVersionFilterTransfer = (new QuoteRequestVersionFilterTransfer())
                ->setQuoteRequest($quoteRequestTransfer);

            $quoteRequestVersionTransfers = $this->quoteRequestRepository
                ->getQuoteRequestVersionCollectionByFilter($quoteRequestVersionFilterTransfer)
                ->getQuoteRequestVersions();

            $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfers->offsetGet(0));

            if (!$quoteRequestTransfer->getIsLatestVersionVisible()) {
                if ($quoteRequestVersionTransfers->offsetExists(1)) {
                    $quoteRequestTransfer->setLatestVisibleVersion($quoteRequestVersionTransfers->offsetGet(1));
                }

                continue;
            }

            $quoteRequestTransfer->setLatestVisibleVersion($quoteRequestTransfer->getLatestVersion());
        }

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    protected function expandQuoteRequestCollectionWithBusinessUnits(
        QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
    ): QuoteRequestCollectionTransfer {
        if (!$quoteRequestCollectionTransfer->getQuoteRequests()->count()) {
            return $quoteRequestCollectionTransfer;
        }

        $companyUserIds = [];

        foreach ($quoteRequestCollectionTransfer->getQuoteRequests() as $quoteRequestTransfer) {
            if (!in_array($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser(), $companyUserIds, true)) {
                $companyUserIds[] = $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser();
            }
        }

        $companyUserTransfers = $this->companyUserFacade
            ->getCompanyUserCollection((new CompanyUserCriteriaFilterTransfer())->setCompanyUserIds($companyUserIds))
            ->getCompanyUsers()
            ->getArrayCopy();

        $companyUserTransfers = $this->mapCompanyUsers($companyUserTransfers);

        foreach ($quoteRequestCollectionTransfer->getQuoteRequests() as $quoteRequestTransfer) {
            $companyUserTransfer = $companyUserTransfers[$quoteRequestTransfer->getCompanyUser()->getIdCompanyUser()] ?? null;
            $quoteRequestTransfer->getCompanyUser()
                ->setCompanyBusinessUnit($companyUserTransfer->getCompanyBusinessUnit());
        }

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer[] $companyUserTransfers
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    protected function mapCompanyUsers(array $companyUserTransfers): array
    {
        $companyUserTransferMap = [];

        foreach ($companyUserTransfers as $companyUserTransfer) {
            $companyUserTransferMap[$companyUserTransfer->getIdCompanyUser()] = $companyUserTransfer;
        }

        return $companyUserTransferMap;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
