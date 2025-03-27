<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Persistence;

use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAsset;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnit;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Propel\Runtime\Exception\InvalidArgumentException;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementPersistenceFactory getFactory()
 */
class SspAssetManagementEntityManager extends AbstractEntityManager implements SspAssetManagementEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function createSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $spySspAssetEntity = $this->getFactory()
            ->createAssetMapper()
            ->mapSspAssetTransferToSpySspAssetEntity($sspAssetTransfer, new SpySspAsset());

        $spySspAssetEntity->save();
        $sspAssetTransfer->setIdSspAsset($spySspAssetEntity->getIdSspAsset());

        $this->createAssetToCompanyBusinessUnitRelations($sspAssetTransfer);

        return $this->getFactory()
            ->createAssetMapper()
            ->mapSpySspAssetEntityToSspAssetTransfer($spySspAssetEntity, $sspAssetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @throws \Propel\Runtime\Exception\InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer
     */
    public function updateSspAsset(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer
    {
        $spySspAssetEntity = $this->getFactory()
            ->createSspAssetQuery()
            ->findOneByIdSspAsset($sspAssetTransfer->getIdSspAssetOrFail());

        if (!$spySspAssetEntity) {
            throw new InvalidArgumentException('Ssp Asset not found');
        }

        $spySspAssetEntity = $this->getFactory()
            ->createAssetMapper()
            ->mapSspAssetTransferToSpySspAssetEntity($sspAssetTransfer, $spySspAssetEntity);

        if ($spySspAssetEntity->isModified()) {
            $spySspAssetEntity->save();
        }

        return $this->getFactory()
            ->createAssetMapper()
            ->mapSpySspAssetEntityToSspAssetTransfer($spySspAssetEntity, $sspAssetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return void
     */
    public function createAssetToCompanyBusinessUnitRelations(
        SspAssetTransfer $sspAssetTransfer
    ): void {
        if (!$sspAssetTransfer->getAssignments()->count()) {
            return;
        }

        foreach ($sspAssetTransfer->getAssignments() as $sspAssetAssignmentTransfer) {
            if (!$sspAssetAssignmentTransfer->getCompanyBusinessUnit()) {
                continue;
            }

            $spySspAssetToCompanyBusinessUnit = new SpySspAssetToCompanyBusinessUnit();
            $spySspAssetToCompanyBusinessUnit
                ->setFkSspAsset($sspAssetTransfer->getIdSspAssetOrFail())
                ->setFkCompanyBusinessUnit($sspAssetAssignmentTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail())
                ->save();
        }
    }

    /**
     * @param int $idSspAsset
     * @param array<int> $businessUnitIds
     *
     * @return void
     */
    public function deleteAssetToCompanyBusinessUnitRelations(int $idSspAsset, array $businessUnitIds): void
    {
        SpySspAssetToCompanyBusinessUnitQuery::create()
            ->filterByFkSspAsset($idSspAsset)
            ->filterByFkCompanyBusinessUnit_In($businessUnitIds)
            ->delete();
    }
}
