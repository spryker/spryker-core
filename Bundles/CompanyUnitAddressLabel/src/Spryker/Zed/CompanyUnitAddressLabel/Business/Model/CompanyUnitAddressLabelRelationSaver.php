<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business\Model;

use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface;

class CompanyUnitAddressLabelRelationSaver implements CompanyUnitAddressLabelRelationSaverInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface
     */
    protected $companyUnitAddressEntityManager;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface
     */
    protected $companyUnitAddressLabelRepository;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface $labelRepository
     */
    public function __construct(
        CompanyUnitAddressLabelEntityManagerInterface $entityManager,
        CompanyUnitAddressLabelRepositoryInterface $labelRepository
    ) {
        $this->companyUnitAddressLabelRepository = $labelRepository;
        $this->companyUnitAddressEntityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function saveLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        $redundantRelationIds = $this->getRedundantLabelToAddressRelationIds($companyUnitAddressTransfer);
        $isSuccess = true;
        $this->companyUnitAddressEntityManager->deleteRedundantLabelToAddressRelations(
            $redundantRelationIds
        );
        $this->companyUnitAddressEntityManager->saveLabelToAddressRelations($companyUnitAddressTransfer);

        return (new CompanyUnitAddressResponseTransfer())
            ->setCompanyUnitAddressTransfer($companyUnitAddressTransfer)
            ->setIsSuccessful($isSuccess);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddress
     *
     * @return int[]
     */
    protected function getRedundantLabelToAddressRelationIds(CompanyUnitAddressTransfer $companyUnitAddress): array
    {
        $actualLabelIds = $this->companyUnitAddressLabelRepository->findCompanyUnitAddressLabelIdsByAddress($companyUnitAddress);
        $validLabelIds = $this->getLabelIds($companyUnitAddress);

        $redundantLabelIds = array_diff($actualLabelIds, $validLabelIds);

        return $this->companyUnitAddressLabelRepository->findCompanyUnitAddressLabelToCompanyUnitAddressRelationIdsByAddressIdAndLabelIds(
            $companyUnitAddress->getIdCompanyUnitAddress(),
            $redundantLabelIds
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return array
     */
    protected function getLabelIds(CompanyUnitAddressTransfer $companyUnitAddressTransfer): array
    {
        $result = [];
        if (empty($companyUnitAddressTransfer->getLabelCollection())) {
            return $result;
        }
        $labelTransfers = $companyUnitAddressTransfer->getLabelCollection()->getLabels();

        foreach ($labelTransfers as $labelTransfer) {
            $result[] = $labelTransfer->getIdCompanyUnitAddressLabel();
        }

        return $result;
    }
}
