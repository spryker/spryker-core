<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Deleter;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface;

class MerchantRelationRequestDeleter implements MerchantRelationRequestDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface
     */
    protected MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface
     */
    protected MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface
     */
    protected MerchantRelationRequestExtractorInterface $merchantRelationRequestExtractor;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig
     */
    protected MerchantRelationRequestConfig $merchantRelationRequestConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\MerchantRelationRequestExtractorInterface $merchantRelationRequestExtractor
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     */
    public function __construct(
        MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager,
        MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository,
        MerchantRelationRequestExtractorInterface $merchantRelationRequestExtractor,
        MerchantRelationRequestConfig $merchantRelationRequestConfig
    ) {
        $this->merchantRelationRequestEntityManager = $merchantRelationRequestEntityManager;
        $this->merchantRelationRequestRepository = $merchantRelationRequestRepository;
        $this->merchantRelationRequestExtractor = $merchantRelationRequestExtractor;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function deleteCompanyUserMerchantRelationRequests(CompanyUserTransfer $companyUserTransfer): void
    {
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())->addIdCompanyUser($companyUserTransfer->getIdCompanyUserOrFail()),
            );
        $readCollectionBatchSize = $this->merchantRelationRequestConfig
            ->getReadMerchantRelationRequestCollectionBatchSize();

        $this->getTransactionHandler()->handleTransaction(
            function () use ($merchantRelationRequestCriteriaTransfer, $readCollectionBatchSize): void {
                $this->executeDeleteCompanyUserMerchantRelationRequestsTransaction(
                    $merchantRelationRequestCriteriaTransfer,
                    $readCollectionBatchSize,
                );
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function deleteCompanyBusinessUnitMerchantRelationRequests(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): void {
        $merchantRelationRequestCriteriaTransfer = (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions(
                (new MerchantRelationRequestConditionsTransfer())
                    ->addIdOwnerCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail()),
            );
        $readCollectionBatchSize = $this->merchantRelationRequestConfig
            ->getReadMerchantRelationRequestCollectionBatchSize();

        $this->getTransactionHandler()->handleTransaction(
            function () use ($merchantRelationRequestCriteriaTransfer, $readCollectionBatchSize, $companyBusinessUnitTransfer): void {
                $this->executeCompanyBusinessUnitMerchantRelationRequestsDeleteTransaction(
                    $merchantRelationRequestCriteriaTransfer,
                    $companyBusinessUnitTransfer,
                    $readCollectionBatchSize,
                );
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     * @param int $readCollectionBatchSize
     *
     * @return void
     */
    protected function executeDeleteCompanyUserMerchantRelationRequestsTransaction(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer,
        int $readCollectionBatchSize
    ): void {
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($readCollectionBatchSize);
            $merchantRelationRequestCriteriaTransfer->setPagination($paginationTransfer);

            $merchantRelationRequestCollectionTransfer = $this->merchantRelationRequestRepository
                ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

            if (!count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests())) {
                break;
            }

            $this->deleteMerchantRelationRequestRelatedEntities($merchantRelationRequestCollectionTransfer);

            $offset += $readCollectionBatchSize;
        } while (
            count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()) !== 0
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param int $readCollectionBatchSize
     *
     * @return void
     */
    protected function executeCompanyBusinessUnitMerchantRelationRequestsDeleteTransaction(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        int $readCollectionBatchSize
    ): void {
        $offset = 0;

        do {
            $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($readCollectionBatchSize);
            $merchantRelationRequestCriteriaTransfer->setPagination($paginationTransfer);

            $merchantRelationRequestCollectionTransfer = $this->merchantRelationRequestRepository
                ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);

            $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer = (new MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer())
                ->addIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail());

            $this->merchantRelationRequestEntityManager->deleteMerchantRelationRequestToCompanyBusinessUnitCollection(
                $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer,
            );

            if (!$merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->count()) {
                return;
            }

            $this->deleteMerchantRelationRequestRelatedEntities($merchantRelationRequestCollectionTransfer);

            $offset += $readCollectionBatchSize;
        } while (
            count($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()) !== 0
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return void
     */
    protected function deleteMerchantRelationRequestRelatedEntities(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): void {
        $merchantRelationRequestIds = $this->merchantRelationRequestExtractor->extractMerchantRelationRequestIds(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests(),
        );

        $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer = (new MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer())
            ->setMerchantRelationRequestIds($merchantRelationRequestIds);
        $merchantRelationRequestDeleteCriteriaTransfer = (new MerchantRelationRequestDeleteCriteriaTransfer())
            ->setMerchantRelationRequestIds($merchantRelationRequestIds);

        $this->merchantRelationRequestEntityManager->deleteMerchantRelationRequestToCompanyBusinessUnitCollection(
            $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer,
        );
        $this->merchantRelationRequestEntityManager->deleteMerchantRelationRequestCollection(
            $merchantRelationRequestDeleteCriteriaTransfer,
        );
    }
}
