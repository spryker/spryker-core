<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileDirectory;
use Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes;

class FileDirectoryLocalizedAttributesSaver implements FileDirectoryLocalizedAttributesSaverInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return void
     */
    public function saveFileLocalizedAttributes(SpyFileDirectory $fileDirectory, FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $localizedAttributesToSave = $fileDirectoryTransfer->getFileDirectoryLocalizedAttributes();
        $existingFileDirectoryLocalizedAttributes = $fileDirectory->getSpyFileDirectoryLocalizedAttributess()->toKeyIndex('fkLocale');

        if (empty($existingFileDirectoryLocalizedAttributes)) {
            $this->createNewLocalizedAttributes($fileDirectory, $localizedAttributesToSave);
            return;
        }

        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $idLocale = $localizedAttribute->getLocale()->getIdLocale();

            if (!empty($existingFileLocalizedAttributes[$idLocale])) {
                $this->updateLocalizedAttribute($existingFileLocalizedAttributes[$idLocale], $localizedAttribute);
                continue;
            }

            $this->createNewLocalizedAttributes($fileDirectory, [$localizedAttribute]);
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectory $fileDirectory
     * @param array $localizedAttributesToSave
     *
     * @return void
     */
    protected function createNewLocalizedAttributes(SpyFileDirectory $fileDirectory, $localizedAttributesToSave)
    {
        foreach ($localizedAttributesToSave as $localizedAttribute) {
            $spyLocalizedAttribute = new SpyFileDirectoryLocalizedAttributes();
            $spyLocalizedAttribute->fromArray($localizedAttribute->toArray());
            $spyLocalizedAttribute->setFkLocale($localizedAttribute->getLocale()->getIdLocale());

            $fileDirectory->addSpyFileDirectoryLocalizedAttributes($spyLocalizedAttribute);
            $spyLocalizedAttribute->save();
        }

        $fileDirectory->save();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileDirectoryLocalizedAttributes $existingAttribute
     * @param \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer $newAttribute
     *
     * @return void
     */
    protected function updateLocalizedAttribute(
        SpyFileDirectoryLocalizedAttributes $existingAttribute,
        FileDirectoryLocalizedAttributesTransfer $newAttribute
    ) {
        $existingAttribute->fromArray($newAttribute->toArray());
        $existingAttribute->save();
    }
}
