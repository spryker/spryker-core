<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress;

class CompanyUnitAddressMapper implements CompanyUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $unitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapEntityTransferToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer,
        CompanyUnitAddressTransfer $unitAddressTransfer
    ): CompanyUnitAddressTransfer {

        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())->fromArray(
            $unitAddressEntityTransfer->toArray(),
            true
        );

        $companyUnitAddressTransfer->setIso2Code($unitAddressEntityTransfer->getCountry()->getIso2Code());

        $companyBusinessUnitTransfers = $this->mapCompanyBusinessUnitCollection($unitAddressEntityTransfer);
        $companyUnitAddressTransfer->setCompanyBusinessUnits($companyBusinessUnitTransfers);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function mapCompanyBusinessUnitCollection(SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer): CompanyBusinessUnitCollectionTransfer
    {
        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        foreach ($companyUnitAddressEntityTransfer->getSpyCompanyUnitAddressToCompanyBusinessUnits() as $companyUnitAddressToCompanyBusinessUnit) {
            $companyBusinessUnitEntityTransfer = $companyUnitAddressToCompanyBusinessUnit->getCompanyBusinessUnit();

            if ($companyBusinessUnitEntityTransfer->getIdCompanyBusinessUnit()) {
                $companyBusinessUnitTransfer = $this->mapEntityToCompanyBusinessUnitTransfer($companyUnitAddressToCompanyBusinessUnit);
                $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);
            }
        }

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer
     */
    public function mapCompanyUnitAddressTransferToEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
    ): SpyCompanyUnitAddressEntityTransfer {
        $companyUnitAddressEntityTransfer = (new SpyCompanyUnitAddressEntityTransfer())->fromArray(
            $companyUnitAddressTransfer->modifiedToArray(),
            true
        );

        return $companyUnitAddressEntityTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddress $companyUnitAddressEntity,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        $companyUnitAddressTransfer = $companyUnitAddressTransfer->fromArray(
            $companyUnitAddressEntity->toArray(),
            true
        );

        $companyUnitAddressTransfer->setIso2Code($companyUnitAddressEntity->getCountry()->getIso2Code());

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress
     */
    public function mapCompanyUnitAddressTransferToCompanyUnitAddressEntity(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddress $companyUnitAddressEntity
    ): SpyCompanyUnitAddress {
        $companyUnitAddressEntity->fromArray($companyUnitAddressTransfer->toArray());

        return $companyUnitAddressEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer[] $companyUnitAddressToCompanyBusinessUnitEntities
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer[]
     */
    public function mapEntitiesToCompanyBusinessUnitTransfers(
        array $companyUnitAddressToCompanyBusinessUnitEntities
    ): array {
        $companyBusinessUnitIndex = [];
        foreach ($companyUnitAddressToCompanyBusinessUnitEntities as $companyUnitAddressToCompanyBusinessUnitEntity) {
            $idCompanyUnitAddress = $companyUnitAddressToCompanyBusinessUnitEntity->getFkCompanyUnitAddress();
            if (!isset($companyBusinessUnitIndex[$idCompanyUnitAddress])) {
                $companyBusinessUnitIndex[$idCompanyUnitAddress] = new CompanyBusinessUnitCollectionTransfer();
            }
            $companyBusinessUnitTransfer = $this->mapEntityToCompanyBusinessUnitTransfer($companyUnitAddressToCompanyBusinessUnitEntity);
            $companyBusinessUnitIndex[$idCompanyUnitAddress]->addCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitIndex;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer $companyUnitAddressToCompanyBusinessUnitEntity
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function mapEntityToCompanyBusinessUnitTransfer(
        SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer $companyUnitAddressToCompanyBusinessUnitEntity
    ): CompanyBusinessUnitTransfer {
        return (new CompanyBusinessUnitTransfer())
            ->fromArray($companyUnitAddressToCompanyBusinessUnitEntity->toArray(), true);
    }
}
