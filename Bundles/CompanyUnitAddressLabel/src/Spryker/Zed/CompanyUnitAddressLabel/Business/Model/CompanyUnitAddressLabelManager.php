<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business\Model;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface;

class CompanyUnitAddressLabelManager implements CompanyUnitAddressLabelManagerInterface
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
     * @return void
     */
    public function saveLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $redundantRelations = $this->getRedundantLabelToAddressRelations($companyUnitAddressTransfer);
        $this->entityManager->deleteRedundantLabelToAddressRelations(
            array_map(
                function (SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer $item) {
                    return $item->getIdCompanyUnitAddressLabelToCompanyUnitAddress();
                },
                $redundantRelations
            )
        );
        $this->entityManager->saveLabelToAddressRelations($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddress
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer[]
     */
    public function getRedundantLabelToAddressRelations(CompanyUnitAddressTransfer $companyUnitAddress): array
    {
        $relations = $this->labelRepository->findCompanyUnitAddressLabelToCompanyUnitAddressRelations(
            $companyUnitAddress->getIdCompanyUnitAddress()
        );

        return array_filter(
            (array)$relations,
            function (SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer $relation) use ($companyUnitAddress) {
                return $this->isRedundant($relation, $companyUnitAddress);
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer $relation
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddress
     *
     * @return bool
     */
    protected function isRedundant(
        SpyCompanyUnitAddressLabelToCompanyUnitAddressEntityTransfer $relation,
        CompanyUnitAddressTransfer $companyUnitAddress
    ): bool {
        if (empty($companyUnitAddress->getLabelCollection())) {
            return true;
        }

        $labels = $companyUnitAddress->getLabelCollection()->getLabels();
        $addressId = $companyUnitAddress->getIdCompanyUnitAddress();

        foreach ($labels as $label) {
            $labelId = $label->getIdCompanyUnitAddressLabel();
            if ($labelId === $relation->getFkCompanyUnitAddressLabel() &&
                $addressId === $relation->getFkCompanyUnitAddress()
            ) {
                return false;
            }
        }

        return true;
    }
}
