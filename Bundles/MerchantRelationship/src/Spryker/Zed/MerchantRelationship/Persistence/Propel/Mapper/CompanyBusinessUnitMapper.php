<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Propel\Runtime\Collection\ObjectCollection;

class CompanyBusinessUnitMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit> $merchantRelationshipToCompanyBusinessUnitEntities
     *
     * @return array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>>
     */
    public function mapMerchantRelationRequestToCompanyBusinessUnitEntitiesToCompanyBusinessUnitTransfers(
        ObjectCollection $merchantRelationshipToCompanyBusinessUnitEntities
    ): array {
        $companyBusinessUnitTransfersGroupedByIdMerchantRelationship = [];
        /** @var \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnit $merchantRelationshipToCompanyBusinessUnitEntity */
        foreach ($merchantRelationshipToCompanyBusinessUnitEntities as $merchantRelationshipToCompanyBusinessUnitEntity) {
            $idMerchantRelationship = $merchantRelationshipToCompanyBusinessUnitEntity->getFkMerchantRelationship();
            $companyBusinessUnitTransfer = $this->mapCompanyBusinessEntityToCompanyBusinessTransfer(
                $merchantRelationshipToCompanyBusinessUnitEntity->getCompanyBusinessUnit(),
                new CompanyBusinessUnitTransfer(),
            );

            $companyBusinessUnitTransfersGroupedByIdMerchantRelationship[$idMerchantRelationship][] = $companyBusinessUnitTransfer;
        }

        return $companyBusinessUnitTransfersGroupedByIdMerchantRelationship;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function mapCompanyBusinessEntityToCompanyBusinessTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        return $companyBusinessUnitTransfer->fromArray($companyBusinessUnitEntity->toArray(), true);
    }
}
