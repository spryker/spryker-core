<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetStorage;

class SspAssetStorageEntityMapper implements SspAssetStorageEntityMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_ID_ASSET = 'id_asset';

    /**
     * @var string
     */
    protected const KEY_REFERENCE = 'reference';

    /**
     * @var string
     */
    protected const KEY_BUSINESS_UNIT_IDS = 'business_unit_ids';

    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_COMPANY_IDS = 'company_ids';

    /**
     * @var string
     */
    protected const KEY_SERIAL_NUMBER = 'serial_number';

    /**
     * @var string
     */
    protected const KEY_MODEL_IDS = 'model_ids';

    /**
     * @var string
     */
    protected const KEY_ID_OWNER_BUSINESS_UNIT = 'id_owner_business_unit';

    /**
     * @var string
     */
    protected const KEY_ID_OWNER_COMPANY_ID = 'id_owner_company_id';

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_DATA_KEY_BUSINESS_UNIT_IDS = 'businessUnitIds';

    /**
     * @var string
     */
    protected const BUSINESS_UNIT_DATA_KEY_COMPANY_IDS = 'companyIds';

    public function mapSspAssetTransferToSspAssetStorageEntity(
        SspAssetTransfer $sspAssetTransfer,
        SpySspAssetStorage $sspAssetStorageEntity
    ): SpySspAssetStorage {
        $sspAssetStorageEntity->setFkSspAsset($sspAssetTransfer->getIdSspAssetOrFail());
        $sspAssetStorageEntity->setData($this->mapSspAssetTransferToStorageData($sspAssetTransfer));
        $sspAssetStorageEntity->setReference($sspAssetTransfer->getReferenceOrFail());

        return $sspAssetStorageEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<string, mixed>
     */
    protected function mapSspAssetTransferToStorageData(SspAssetTransfer $sspAssetTransfer): array
    {
        $businessUnitData = $this->extractBusinessUnitData($sspAssetTransfer);
        $modelIds = $this->extractModelIds($sspAssetTransfer);

        return [
            static::KEY_ID_ASSET => $sspAssetTransfer->getIdSspAsset(),
            static::KEY_BUSINESS_UNIT_IDS => $businessUnitData[static::BUSINESS_UNIT_DATA_KEY_BUSINESS_UNIT_IDS],
            static::KEY_NAME => $sspAssetTransfer->getName(),
            static::KEY_COMPANY_IDS => $businessUnitData[static::BUSINESS_UNIT_DATA_KEY_COMPANY_IDS],
            static::KEY_SERIAL_NUMBER => $sspAssetTransfer->getSerialNumber(),
            static::KEY_MODEL_IDS => $modelIds,
            static::KEY_REFERENCE => $sspAssetTransfer->getReferenceOrFail(),
            static::KEY_ID_OWNER_BUSINESS_UNIT => $sspAssetTransfer->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            static::KEY_ID_OWNER_COMPANY_ID => $sspAssetTransfer->getCompanyBusinessUnitOrFail()->getFkCompanyOrFail(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<string, list<int>>
     */
    protected function extractBusinessUnitData(SspAssetTransfer $sspAssetTransfer): array
    {
        $businessUnitIds = [];
        $companyIds = [];

        foreach ($sspAssetTransfer->getBusinessUnitAssignments() as $businessUnitAssignment) {
            $businessUnitIds[] = $businessUnitAssignment->getCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail();
            $companyIds[] = $businessUnitAssignment->getCompanyBusinessUnitOrFail()->getCompanyOrFail()->getIdCompanyOrFail();
        }

        return [
            static::BUSINESS_UNIT_DATA_KEY_BUSINESS_UNIT_IDS => array_values(array_unique($businessUnitIds)),
            static::BUSINESS_UNIT_DATA_KEY_COMPANY_IDS => array_values(array_unique($companyIds)),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return list<int>
     */
    protected function extractModelIds(SspAssetTransfer $sspAssetTransfer): array
    {
        $modelIds = [];
        foreach ($sspAssetTransfer->getSspModels() as $sspModelTransfer) {
            $modelIds[] = $sspModelTransfer->getIdSspModelOrFail();
        }

        return array_unique($modelIds);
    }
}
