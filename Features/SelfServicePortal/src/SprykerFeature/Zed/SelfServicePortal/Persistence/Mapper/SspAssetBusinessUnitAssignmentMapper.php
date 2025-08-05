<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit;
use Propel\Runtime\Collection\ObjectCollection;

class SspAssetBusinessUnitAssignmentMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit> $sspAssetToCompanyBusinessUnitEntities
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function mapSspAssetToCompanyBusinessUnitEntitiesToSspAssetCollection(
        ObjectCollection $sspAssetToCompanyBusinessUnitEntities,
        SspAssetCollectionTransfer $sspAssetCollectionTransfer
    ): SspAssetCollectionTransfer {
        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            foreach ($sspAssetToCompanyBusinessUnitEntities as $sspAssetToCompanyBusinessUnit) {
                if ($sspAssetToCompanyBusinessUnit->getFkSspAsset() === $sspAssetTransfer->getIdSspAsset()) {
                    $businessUnitAssignmentTransfer = $this->mapSspAssetToCompanyBusinessUnitEntityToSspAssetBusinessUnitAssignmentTransfer(
                        $sspAssetToCompanyBusinessUnit,
                        new SspAssetBusinessUnitAssignmentTransfer(),
                    );

                    $sspAssetTransfer->addBusinessUnitAssignment($businessUnitAssignmentTransfer);
                }
            }
        }

        return $sspAssetCollectionTransfer;
    }

    protected function mapSspAssetToCompanyBusinessUnitEntityToSspAssetBusinessUnitAssignmentTransfer(
        SpySspAssetToCompanyBusinessUnit $sspAssetToCompanyBusinessUnitEntity,
        SspAssetBusinessUnitAssignmentTransfer $sspAssetBusinessUnitAssignmentTransfer
    ): SspAssetBusinessUnitAssignmentTransfer {
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($sspAssetToCompanyBusinessUnitEntity->getFkCompanyBusinessUnit())
            ->setName($sspAssetToCompanyBusinessUnitEntity->getSpyCompanyBusinessUnit()->getName())
            ->setCompany(
                (new CompanyTransfer())
                    ->setIdCompany($sspAssetToCompanyBusinessUnitEntity->getSpyCompanyBusinessUnit()->getFkCompany())
                    ->setName($sspAssetToCompanyBusinessUnitEntity->getSpyCompanyBusinessUnit()->getCompany()->getName()),
            );

        return $sspAssetBusinessUnitAssignmentTransfer
            ->setCompanyBusinessUnit($companyBusinessUnitTransfer)
            ->setAssignedAt($sspAssetToCompanyBusinessUnitEntity->getCreatedAt()?->format('Y-m-d H:i:s'));
    }
}
