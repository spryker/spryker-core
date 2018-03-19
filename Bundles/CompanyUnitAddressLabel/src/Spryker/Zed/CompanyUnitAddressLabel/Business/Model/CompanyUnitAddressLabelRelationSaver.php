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
    protected $entityManager;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface
     */
    protected $labelRepository;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface $entityManager
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface $labelRepository
     */
    public function __construct(
        CompanyUnitAddressLabelEntityManagerInterface $entityManager,
        CompanyUnitAddressLabelRepositoryInterface $labelRepository
    ) {
        $this->labelRepository = $labelRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function saveLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $redundantRelationIds = $this->getRedundantLabelToAddressRelationIds($companyUnitAddressTransfer);
        $this->entityManager->deleteRedundantLabelToAddressRelations(
            $redundantRelationIds
        );
        $this->entityManager->saveLabelToAddressRelations($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddress
     *
     * @return int[]
     */
    protected function getRedundantLabelToAddressRelationIds(CompanyUnitAddressTransfer $companyUnitAddress): array
    {
        $actualLabelIds = $this->labelRepository->findCompanyUnitAddressLabelIdsByAddress($companyUnitAddress);
        $validLabelIds = $this->getLabelIds($companyUnitAddress);

        $redundantLabelIds = array_diff($actualLabelIds, $validLabelIds);

        return $this->labelRepository->findCompanyUnitAddressLabelToCompanyUnitAddressRelationIdsByAddressIdAndLabelIds(
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
        $labels = $companyUnitAddressTransfer->getLabelCollection()->getLabels();

        foreach ($labels as $label) {
            $result[] = $label->getIdCompanyUnitAddressLabel();
        }

        return $result;
    }
}
