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
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     *
     * @return void
     */
    public function saveDatasetLocalizedAttributes(SpyDataset $dataset, SpyDatasetEntityTransfer $saveRequestTransfer)
    {
        $localizedAttributesToSave = $saveRequestTransfer->getSpyDatasetLocalizedAttributess();
        $existingDatasetLocalizedAttributes = $dataset->getSpyDatasetLocalizedAttributess()->toKeyIndex('fkLocale');
        if (empty($existingDatasetLocalizedAttributes)) {
            $this->createNewLocalizedAttributes($dataset, $localizedAttributesToSave);

            return;
        }
        $this->saveNewLocalizedAttributes($dataset, $localizedAttributesToSave, $existingDatasetLocalizedAttributes);
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param array $localizedAttributesToSave
     * @param array $existingDatasetLocalizedAttributes
     *
     * @return void
     */
    protected function saveNewLocalizedAttributes(
        SpyDataset $dataset,
        $localizedAttributesToSave,
        $existingDatasetLocalizedAttributes
    ) {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $idLocale = $localizedAttribute->getLocale()->getIdLocale();
            if (!empty($existingDatasetLocalizedAttributes[$idLocale])) {
                $this->updateLocalizedAttribute($existingDatasetLocalizedAttributes[$idLocale], $localizedAttribute);
                continue;
            }
            $this->createNewLocalizedAttributes($dataset, [$localizedAttribute]);
        }
    }

    /**
     * @param \Orm\Zed\Dataset\Persistence\SpyDataset $dataset
     * @param array $localizedAttributesToSave
     *
     * @return void
     */
    protected function createNewLocalizedAttributes(SpyDataset $dataset, $localizedAttributesToSave)
    {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $spyLocalizedAttribute = new SpyDatasetLocalizedAttributes();
            $spyLocalizedAttribute->fromArray($localizedAttribute->toArray());
            $spyLocalizedAttribute->setFkLocale($localizedAttribute->getLocale()->getIdLocale());
            $spyLocalizedAttribute->setFkDataset($dataset->getIdDataset());
            $spyLocalizedAttribute->save();
            $dataset->addSpyDatasetLocalizedAttributes($spyLocalizedAttribute);
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
