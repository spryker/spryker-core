<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship;

class MerchantRelationshipMapper implements MerchantRelationshipMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship
     */
    public function mapMerchantRelationshipTransferToEntity(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        SpyMerchantRelationship $spyMerchantRelationship
    ): SpyMerchantRelationship {
        $spyMerchantRelationship->fromArray(
            $merchantRelationshipTransfer->modifiedToArray(false)
        );

        return $spyMerchantRelationship;
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapEntityToMerchantRelationshipTransfer(
        SpyMerchantRelationship $spyMerchantRelationship,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $merchantRelationshipTransfer->fromArray(
            $spyMerchantRelationship->toArray(),
            true
        );
        if ($spyMerchantRelationship->getMerchant()) {
            $merchantTransfer = $merchantRelationshipTransfer->getMerchant() ?: new MerchantTransfer();
            $merchantTransfer->fromArray($spyMerchantRelationship->getMerchant()->toArray(), true);
            $merchantRelationshipTransfer->setMerchant($merchantTransfer);
        }
        $merchantRelationshipTransfer->setOwnerCompanyBusinessUnit(
            $this->mapCompanyBusinessUnitEntityToTransfer($spyMerchantRelationship->getCompanyBusinessUnit(), new CompanyBusinessUnitTransfer())
        );

        $merchantRelationshipTransfer = $this->mapAssigneeCompanyBusinessUnits($spyMerchantRelationship, $merchantRelationshipTransfer);

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnit $spyCompanyBusinessUnit
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function mapCompanyBusinessUnitEntityToTransfer(
        SpyCompanyBusinessUnit $spyCompanyBusinessUnit,
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        return $companyBusinessUnitTransfer->fromArray(
            $spyCompanyBusinessUnit->toArray(),
            true
        );
    }

    /**
     * @param \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationship $spyMerchantRelationship
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function mapAssigneeCompanyBusinessUnits(
        SpyMerchantRelationship $spyMerchantRelationship,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits(new CompanyBusinessUnitCollectionTransfer());
        foreach ($spyMerchantRelationship->getSpyMerchantRelationshipToCompanyBusinessUnits() as $spyMerchantRelationshipToCompanyBusinessUnits) {
            $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()
                ->addCompanyBusinessUnit(
                    $this->mapCompanyBusinessUnitEntityToTransfer(
                        $spyMerchantRelationshipToCompanyBusinessUnits->getCompanyBusinessUnit(),
                        new CompanyBusinessUnitTransfer()
                    )
                );
        }

        return $merchantRelationshipTransfer;
    }
}
