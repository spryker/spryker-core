<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business\Model;

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
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function saveLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $redundantRelationIds = $this->getRedundantLabelToAddressRelationIds($companyUnitAddressTransfer);
        $this->companyUnitAddressEntityManager->deleteRedundantLabelToAddressRelations(
            $redundantRelationIds
        );
        $this->companyUnitAddressEntityManager->saveLabelToAddressRelations($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
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
        $labelTransfers = $companyUnitAddressTransfer->getLabelCollection()->getLabels();

        foreach ($labelTransfers as $label) {
            $result[] = $label->getIdCompanyUnitAddressLabel();
        }

        return $result;
    }
}
