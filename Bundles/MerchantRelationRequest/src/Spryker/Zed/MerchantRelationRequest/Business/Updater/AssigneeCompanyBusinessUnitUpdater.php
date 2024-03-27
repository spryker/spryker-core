<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Updater;

use Generated\Shared\Transfer\MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface;

class AssigneeCompanyBusinessUnitUpdater implements AssigneeCompanyBusinessUnitUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface
     */
    protected MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface
     */
    protected MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface
     */
    protected AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager
     * @param \Spryker\Zed\MerchantRelationRequest\Business\Extractor\AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
     */
    public function __construct(
        MerchantRelationRequestRepositoryInterface $merchantRelationRequestRepository,
        MerchantRelationRequestEntityManagerInterface $merchantRelationRequestEntityManager,
        AssigneeCompanyBusinessUnitExtractorInterface $assigneeCompanyBusinessUnitExtractor
    ) {
        $this->merchantRelationRequestRepository = $merchantRelationRequestRepository;
        $this->merchantRelationRequestEntityManager = $merchantRelationRequestEntityManager;
        $this->assigneeCompanyBusinessUnitExtractor = $assigneeCompanyBusinessUnitExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function updateAssigneeCompanyBusinessUnits(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestTransfer {
        $existingCompanyBusinessUnitIds = $this->getExistingCompanyBusinessUnitIds($merchantRelationRequestTransfer);
        $requestedCompanyBusinessUnitIds = $this->assigneeCompanyBusinessUnitExtractor
            ->extractCompanyBusinessUnitIds($merchantRelationRequestTransfer);

        $companyBusinessUnitIdsToDelete = array_diff($existingCompanyBusinessUnitIds, $requestedCompanyBusinessUnitIds);
        $companyBusinessUnitIdsToAdd = array_diff($requestedCompanyBusinessUnitIds, $existingCompanyBusinessUnitIds);

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($merchantRelationRequestTransfer, $companyBusinessUnitIdsToDelete, $companyBusinessUnitIdsToAdd) {
                return $this->executeUpdateAssigneeCompanyBusinessUnitsTransaction(
                    $merchantRelationRequestTransfer,
                    $companyBusinessUnitIdsToDelete,
                    $companyBusinessUnitIdsToAdd,
                );
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return list<int>
     */
    protected function getExistingCompanyBusinessUnitIds(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array
    {
        $companyBusinessUnitIds = [];

        $idMerchantRelationRequest = $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail();
        $existedCompanyBusinessUnitTransfers = $this->merchantRelationRequestRepository
            ->getAssigneeCompanyBusinessUnitsGroupedByIdMerchantRelationRequest([$idMerchantRelationRequest]);

        foreach ($existedCompanyBusinessUnitTransfers[$idMerchantRelationRequest] as $companyBusinessUnitTransfer) {
            $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
        }

        return $companyBusinessUnitIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param array<int, int> $companyBusinessUnitIdsToDelete
     * @param array<int, int> $companyBusinessUnitIdsToAdd
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function executeUpdateAssigneeCompanyBusinessUnitsTransaction(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        array $companyBusinessUnitIdsToDelete,
        array $companyBusinessUnitIdsToAdd
    ): MerchantRelationRequestTransfer {
        if ($companyBusinessUnitIdsToDelete) {
            $merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer = (new MerchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer())
                ->setCompanyBusinessUnitIds($companyBusinessUnitIdsToDelete)
                ->addIdMerchantRelationRequest($merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail());

            $this->merchantRelationRequestEntityManager
                ->deleteMerchantRelationRequestToCompanyBusinessUnitCollection($merchantRelationRequestToCompanyBusinessUnitDeleteCriteriaTransfer);
        }

        if ($companyBusinessUnitIdsToAdd) {
            $this->merchantRelationRequestEntityManager->createAssigneeCompanyBusinessUnits(
                $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail(),
                $companyBusinessUnitIdsToAdd,
            );
        }

        return $merchantRelationRequestTransfer;
    }
}
