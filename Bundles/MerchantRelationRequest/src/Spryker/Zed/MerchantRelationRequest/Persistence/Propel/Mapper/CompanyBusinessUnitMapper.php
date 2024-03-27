<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Propel\Runtime\Collection\ObjectCollection;

class CompanyBusinessUnitMapper
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyMapper
     */
    protected CompanyMapper $companyMapper;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Persistence\Propel\Mapper\CompanyMapper $companyMapper
     */
    public function __construct(CompanyMapper $companyMapper)
    {
        $this->companyMapper = $companyMapper;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $companyBusinessUnitEntity
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function mapCompanyBusinessUnitEntityToCompanyBusinessUnitTransfer(
        SpyCompanyBusinessUnit $companyBusinessUnitEntity,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $companyBusinessUnitTransfer = $companyBusinessUnitTransfer
            ->fromArray($companyBusinessUnitEntity->toArray(), true);

        $companyTransfer = $this->companyMapper->mapCompanyEntityToCompanyTransfer(
            $companyBusinessUnitEntity->getCompany(),
            new CompanyTransfer(),
        );

        return $companyBusinessUnitTransfer->setCompany($companyTransfer);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnit> $merchantRelationRequestToCompanyBusinessUnitEntities
     *
     * @return array<int, list<\Generated\Shared\Transfer\CompanyBusinessUnitTransfer>>
     */
    public function mapMerchantRelationRequestToCompanyBusinessUnitEntitiesToCompanyBusinessUnitTransfers(
        ObjectCollection $merchantRelationRequestToCompanyBusinessUnitEntities
    ): array {
        $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest = [];

        foreach ($merchantRelationRequestToCompanyBusinessUnitEntities as $merchantRelationRequestToCompanyBusinessUnitEntity) {
            $idMerchantRelationRequest = $merchantRelationRequestToCompanyBusinessUnitEntity->getFkMerchantRelationRequest();
            $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest[$idMerchantRelationRequest][] =
                (new CompanyBusinessUnitTransfer())->fromArray(
                    $merchantRelationRequestToCompanyBusinessUnitEntity->getCompanyBusinessUnit()->toArray(),
                    true,
                );
        }

        return $companyBusinessUnitTransfersGroupedByIdMerchantRelationRequest;
    }
}
