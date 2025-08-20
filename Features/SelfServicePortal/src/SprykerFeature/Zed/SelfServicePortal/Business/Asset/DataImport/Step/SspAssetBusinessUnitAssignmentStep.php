<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step;

use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\DataSet\SspAssetDataSetInterface;

class SspAssetBusinessUnitAssignmentStep implements DataImportStepInterface
{
    public function execute(DataSetInterface $dataSet): void
    {
        $assignedBusinessUnitIds = $dataSet[SspAssetDataSetInterface::ASSIGNED_BUSINESS_UNIT_IDS] ?? [];

        if (!$assignedBusinessUnitIds) {
            return;
        }

        $sspAssetId = $dataSet[SspAssetDataSetInterface::ID_SSP_ASSET];

        foreach ($assignedBusinessUnitIds as $businessUnitId) {
            $assetToBusinessUnitEntity = SpySspAssetToCompanyBusinessUnitQuery::create()
                ->filterByFkSspAsset($sspAssetId)
                ->filterByFkCompanyBusinessUnit($businessUnitId)
                ->findOneOrCreate();

            if ($assetToBusinessUnitEntity->isNew()) {
                $assetToBusinessUnitEntity->save();
            }
        }
    }
}
