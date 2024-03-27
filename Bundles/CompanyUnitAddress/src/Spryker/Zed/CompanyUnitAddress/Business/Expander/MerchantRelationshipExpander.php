<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface;

class MerchantRelationshipExpander implements MerchantRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface
     */
    protected CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader
     */
    public function __construct(CompanyBusinessUnitAddressReaderInterface $companyBusinessUnitAddressReader)
    {
        $this->companyBusinessUnitAddressReader = $companyBusinessUnitAddressReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function expandMerchantRelationshipCollectionWithCompanyUnitAddress(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer {
        $companyBusinessUnitIds = $this->extractCompanyBusinessUnitIds($merchantRelationshipCollectionTransfer);
        $companyUnitAddressCollectionTransfer = $this->companyBusinessUnitAddressReader->getCompanyUnitAddressCollectionByCompanyBusinessUnitIds(
            $companyBusinessUnitIds,
        );
        $companyUnitAddressTransfersGroupedByIdCompanyBusinessUnit = $this->getCompanyUnitAddressTransfersGroupedByIdCompanyBusinessUnit(
            $companyUnitAddressCollectionTransfer,
        );

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $this->expandCompanyBusinessUnitCollectionWithAddresses(
                $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail(),
                $companyUnitAddressTransfersGroupedByIdCompanyBusinessUnit,
            );
        }

        return $merchantRelationshipCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     * @param array<int, list<\Generated\Shared\Transfer\CompanyUnitAddressTransfer>> $companyUnitAddressTransfersGroupedByIdCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function expandCompanyBusinessUnitCollectionWithAddresses(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer,
        array $companyUnitAddressTransfersGroupedByIdCompanyBusinessUnit
    ): CompanyBusinessUnitCollectionTransfer {
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            $companyUnitAddressTransfers = $companyUnitAddressTransfersGroupedByIdCompanyBusinessUnit[$idCompanyBusinessUnit] ?? [];

            $companyUnitAddressCollectionTransfer = (new CompanyUnitAddressCollectionTransfer())->setCompanyUnitAddresses(
                new ArrayObject($companyUnitAddressTransfers),
            );
            $companyBusinessUnitTransfer->setAddressCollection($companyUnitAddressCollectionTransfer);
        }

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return array<int, int>
     */
    protected function extractCompanyBusinessUnitIds(MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer): array
    {
        $companyBusinessUnitIds = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            foreach ($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $companyBusinessUnitIds[] = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            }
        }

        return array_unique($companyBusinessUnitIds);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\CompanyUnitAddressTransfer>>
     */
    protected function getCompanyUnitAddressTransfersGroupedByIdCompanyBusinessUnit(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): array {
        $groupedCompanyUnitAddressTransfers = [];
        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            foreach ($companyUnitAddressTransfer->getCompanyBusinessUnits()->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
                $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
                $groupedCompanyUnitAddressTransfers[$idCompanyBusinessUnit][] = $companyUnitAddressTransfer;
            }
        }

        return $groupedCompanyUnitAddressTransfers;
    }
}
