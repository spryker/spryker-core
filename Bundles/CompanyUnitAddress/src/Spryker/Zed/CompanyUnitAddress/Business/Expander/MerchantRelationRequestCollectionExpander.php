<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Expander;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;

class MerchantRelationRequestCollectionExpander implements MerchantRelationRequestCollectionExpanderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface
     */
    protected CompanyUnitAddressRepositoryInterface $companyUnitAddressRepository;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface $companyUnitAddressRepository
     */
    public function __construct(CompanyUnitAddressRepositoryInterface $companyUnitAddressRepository)
    {
        $this->companyUnitAddressRepository = $companyUnitAddressRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandMerchantRelationRequestCollectionWithAssigneeCompanyBusinessUnitAddress(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit = $this->getCompanyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit(
            $merchantRelationRequestCollectionTransfer,
        );
        $companyUnitAddressCollectionTransfer = $this->getCompanyUnitAddressCollection(
            array_keys($companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit),
        );

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            foreach ($companyUnitAddressTransfer->getCompanyBusinessUnits()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $this->expandCompanyBusinessUnitTransfers($companyBusinessUnitTransfer, $companyUnitAddressTransfer, $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit);
            }
        }

        return $merchantRelationRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>> $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit
     *
     * @return void
     */
    protected function expandCompanyBusinessUnitTransfers(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        array $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit
    ): void {
        $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();

        if (!isset($companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit[$idCompanyBusinessUnit])) {
            return;
        }

        foreach ($companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit[$idCompanyBusinessUnit] as $companyBusinessUnitTransferToExpand) {
            if ($companyBusinessUnitTransferToExpand->getAddressCollection() === null) {
                $companyBusinessUnitTransferToExpand->setAddressCollection(new CompanyUnitAddressCollectionTransfer());
            }

            $companyBusinessUnitTransferToExpand->getAddressCollectionOrFail()->addCompanyUnitAddress($companyUnitAddressTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>>
     */
    protected function getCompanyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): array {
        $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit = [];
        foreach ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            foreach ($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
                $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit[$idCompanyBusinessUnit][] = $companyBusinessUnitTransfer;
            }
        }

        return $companyBusinessUnitTransfersGroupedByIdCompanyBusinessUnit;
    }

    /**
     * @param list<int> $companyBusinessUnits
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    protected function getCompanyUnitAddressCollection(array $companyBusinessUnits): CompanyUnitAddressCollectionTransfer
    {
        $companyUnitAddressCriteriaFilterTransfer = (new CompanyUnitAddressCriteriaFilterTransfer())
            ->setCompanyBusinessUnitIds($companyBusinessUnits)
            ->setWithCompanyBusinessUnits(true);

        return $this->companyUnitAddressRepository
            ->getCompanyBusinessUnitAddressesByCriteriaFilter($companyUnitAddressCriteriaFilterTransfer);
    }
}
