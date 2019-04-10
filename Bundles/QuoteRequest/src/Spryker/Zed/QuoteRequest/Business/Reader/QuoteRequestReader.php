<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Reader;

use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestCriteriaTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionFilterTransfer;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
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
     * @param \Generated\Shared\Transfer\QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findQuoteRequest(QuoteRequestCriteriaTransfer $quoteRequestCriteriaTransfer): ?QuoteRequestTransfer
    {
        $quoteRequestCriteriaTransfer->requireQuoteRequestReference();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->fromArray($quoteRequestCriteriaTransfer->toArray(), true);

        if ($quoteRequestCriteriaTransfer->getIdCompanyUser()) {
            $quoteRequestFilterTransfer->setCompanyUser(
                (new CompanyUserTransfer())->setIdCompanyUser($quoteRequestCriteriaTransfer->getIdCompanyUser())
            );
        }

        $quoteRequestTransfers = $this
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
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

            if ($quoteRequestTransfer->getIsLatestVersionHidden()) {
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
            if (!in_array($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser(), $companyUserIds)) {
                $companyUserIds[] = $quoteRequestTransfer->getCompanyUser()->getIdCompanyUser();
            }
        }

        $companyUserTransfers = $this->companyUserFacade
            ->getCompanyUserCollection((new CompanyUserCriteriaFilterTransfer())->setCompanyUserIds($companyUserIds))
            ->getCompanyUsers()
            ->getArrayCopy();

        $companyUserTransfers = $this->filterCompanyUsers($companyUserTransfers);

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
    protected function filterCompanyUsers(array $companyUserTransfers): array
    {
        $filteredCompanyUserTransfers = [];

        foreach ($companyUserTransfers as $companyUserTransfer) {
            $filteredCompanyUserTransfers[$companyUserTransfer->getIdCompanyUser()] = $companyUserTransfer;
        }

        return $filteredCompanyUserTransfers;
    }
}
