<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDataset;
use Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributes;

class DatasetLocalizedAttributesSaver implements DatasetLocalizedAttributesSaverInterface
{
    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetLocalizedAttributes(
        SpyDataset $datasetEntity,
        SpyDatasetEntityTransfer $saveRequestTransfer
    ) {
        $localizedAttributes = $saveRequestTransfer->getSpyDatasetLocalizedAttributess();
        $existingDatasetLocalizedAttributes = $datasetEntity->getSpyDatasetLocalizedAttributess()
            ->toKeyIndex('fkLocale');
        if (empty($existingDatasetLocalizedAttributes)) {
            $this->createNewLocalizedAttributes($datasetEntity, $localizedAttributes);

            return;
        }
        $this->saveNewLocalizedAttributes($datasetEntity, $localizedAttributes, $existingDatasetLocalizedAttributes);
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param array $localizedAttributesToSave
     * @param array $existingDatasetLocalizedAttributes
     *
     * @return void
     */
    protected function saveNewLocalizedAttributes(
        SpyDataset $datasetEntity,
        $localizedAttributesToSave,
        $existingDatasetLocalizedAttributes
    ) {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $idLocale = $localizedAttribute->getLocale()->getIdLocale();
            if (!empty($existingDatasetLocalizedAttributes[$idLocale])) {
                $this->updateLocalizedAttribute($existingDatasetLocalizedAttributes[$idLocale], $localizedAttribute);
                continue;
            }
            $this->createNewLocalizedAttributes($datasetEntity, [$localizedAttribute]);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $datasetEntity
     * @param array $localizedAttributesToSave
     *
     * @return void
     */
    protected function createNewLocalizedAttributes(SpyDataset $datasetEntity, $localizedAttributesToSave)
    {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $spyLocalizedAttribute = new SpyDatasetLocalizedAttributes();
            $spyLocalizedAttribute->fromArray($localizedAttribute->toArray());
            $spyLocalizedAttribute->setFkLocale($localizedAttribute->getLocale()->getIdLocale());
            $spyLocalizedAttribute->setFkDataset($datasetEntity->getIdDataset());
            $spyLocalizedAttribute->save();
            $datasetEntity->addSpyDatasetLocalizedAttributes($spyLocalizedAttribute);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetLocalizedAttributes $existingAttribute
     * @param \Generated\Shared\Transfer\SpyDatasetLocalizedAttributesEntityTransfer $newAttribute
     *
     * @return void
     */
    protected function updateLocalizedAttribute(
        SpyDatasetLocalizedAttributes $existingAttribute,
        SpyDatasetLocalizedAttributesEntityTransfer $newAttribute
    ) {
        $existingAttribute->fromArray($newAttribute->toArray());
        $existingAttribute->save();
    }
}
