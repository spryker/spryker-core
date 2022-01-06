<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress;

class CompanyUnitAddressMapper implements CompanyUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapCompanyUnitAddressEntityTransferToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        $companyUnitAddressTransfer->fromArray($companyUnitAddressEntityTransfer->toArray(), true);

        $companyUnitAddressTransfer->setIso2Code($companyUnitAddressEntityTransfer->getCountry()->getIso2Code());

        $companyBusinessUnitTransfers = $this->mapCompanyBusinessUnitCollection($companyUnitAddressEntityTransfer);
        $companyUnitAddressTransfer->setCompanyBusinessUnits($companyBusinessUnitTransfers);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function mapCompanyBusinessUnitCollection(
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        foreach ($companyUnitAddressEntityTransfer->getSpyCompanyUnitAddressToCompanyBusinessUnits() as $companyUnitAddressToCompanyBusinessUnit) {
            $companyBusinessUnitEntityTransfer = $companyUnitAddressToCompanyBusinessUnit->getCompanyBusinessUnit();
            if (!$companyBusinessUnitEntityTransfer || $companyBusinessUnitEntityTransfer->getIdCompanyBusinessUnit() === null) {
                continue;
            }

            $companyBusinessUnitTransfer = $this->mapEntityToCompanyBusinessUnitTransfer(
                $companyBusinessUnitEntityTransfer,
                new CompanyBusinessUnitTransfer(),
            );
            $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer
     */
    public function mapCompanyUnitAddressTransferToEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
    ): SpyCompanyUnitAddressEntityTransfer {
        return $companyUnitAddressEntityTransfer->fromArray(
            $companyUnitAddressTransfer->modifiedToArray(),
            true,
        );
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
            true,
        );

        $companyUnitAddressTransfer->setIso2Code($companyUnitAddressEntity->getCountry()->getIso2Code());
        if ($companyUnitAddressEntity->getFkCompany()) {
            $companyUnitAddressTransfer->setCompany(
                (new CompanyTransfer())->fromArray($companyUnitAddressEntity->getCompany()->toArray(), true),
            );
        }

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
     * @param array<\Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer> $companyUnitAddressToCompanyBusinessUnitEntities
     *
     * @return array<\Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer>
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

            $companyBusinessUnitEntityTransfer = $companyUnitAddressToCompanyBusinessUnitEntity->getCompanyBusinessUnit();
            if (!$companyBusinessUnitEntityTransfer || $companyBusinessUnitEntityTransfer->getIdCompanyBusinessUnit() === null) {
                continue;
            }

            $companyBusinessUnitTransfer = $this->mapEntityToCompanyBusinessUnitTransfer(
                $companyBusinessUnitEntityTransfer,
                new CompanyBusinessUnitTransfer(),
            );
            $companyBusinessUnitIndex[$idCompanyUnitAddress]->addCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitIndex;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer $companyBusinessUnitEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapEntityToCompanyBusinessUnitTransfer(
        SpyCompanyBusinessUnitEntityTransfer $companyBusinessUnitEntityTransfer,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
            return $companyBusinessUnitTransfer->fromArray($companyBusinessUnitEntityTransfer->toArray(), true);
    }
}
