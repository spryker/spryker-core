<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes;

class FileLocalizedAttributesSaver implements FileLocalizedAttributesSaverInterface
{
    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $fileEntity
     * @param \Generated\Shared\Transfer\FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer
     *
     * @return void
     */
    public function saveLocalizedFileAttributes(SpyFile $fileEntity, FileManagerSaveRequestTransfer $fileManagerSaveRequestTransfer)
    {
        $localizedAttributesToSave = $fileManagerSaveRequestTransfer->getLocalizedAttributes();
        $existingFileLocalizedAttributes = $fileEntity->getSpyFileLocalizedAttributess()->toKeyIndex('fkLocale');

        if (empty($existingFileLocalizedAttributes)) {
            $this->createLocalizedAttributes($fileEntity, $localizedAttributesToSave);
            return;
        }

        $this->updateOrCreateNew($localizedAttributesToSave, $existingFileLocalizedAttributes, $fileEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer[] $localizedAttributes
     * @param array $existingFileLocalizedAttributes
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     *
     * @return void
     */
    protected function updateOrCreateNew($localizedAttributes, $existingFileLocalizedAttributes, SpyFile $file)
    {
        foreach ($localizedAttributes as $localizedAttribute) {
            $localizedAttribute->requireLocale();
            $idLocale = $localizedAttribute->getLocale()->getIdLocale();

            if (!empty($existingFileLocalizedAttributes[$idLocale])) {
                $this->updateLocalizedAttribute($existingFileLocalizedAttributes[$idLocale], $localizedAttribute);
                continue;
            }

            $this->createLocalizedAttributes($file, [$localizedAttribute]);
        }
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFile $file
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributes
     *
     * @return void
     */
    protected function createLocalizedAttributes(SpyFile $file, $localizedAttributes)
    {
        foreach ($localizedAttributes as $localizedAttribute) {
            $spyLocalizedAttribute = new SpyFileLocalizedAttributes();
            $spyLocalizedAttribute->fromArray($localizedAttribute->toArray());
            $spyLocalizedAttribute->setFkLocale($localizedAttribute->getLocale()->getIdLocale());

            $file->addSpyFileLocalizedAttributes($spyLocalizedAttribute);
            $spyLocalizedAttribute->save();
        }

        $file->save();
    }

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileLocalizedAttributes $existingAttribute
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $newAttribute
     *
     * @return void
     */
    protected function updateLocalizedAttribute(SpyFileLocalizedAttributes $existingAttribute, FileLocalizedAttributesTransfer $newAttribute)
    {
        $existingAttribute->fromArray($newAttribute->toArray());
        $existingAttribute->save();
    }
}
