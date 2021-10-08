<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileLocalizedAttributes;

use Generated\Shared\Transfer\FileLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;

class FileLocalizedAttributesSaver implements FileLocalizedAttributesSaverInterface
{
    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface $entityManager
     */
    public function __construct(FileManagerEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    public function save(FileManagerDataTransfer $fileManagerDataTransfer)
    {
        foreach ($fileManagerDataTransfer->getFileLocalizedAttributes() as $fileLocalizedAttributesTransfer) {
            $this->prepareFileLocalizedAttributesTransfer($fileLocalizedAttributesTransfer, $fileManagerDataTransfer);
            $this->entityManager->saveFileLocalizedAttribute($fileLocalizedAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer
     * @param \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer
     *
     * @return void
     */
    protected function prepareFileLocalizedAttributesTransfer(
        FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer,
        FileManagerDataTransfer $fileManagerDataTransfer
    ): void {
        $fileTransfer = $fileManagerDataTransfer->getFile();
        if ($fileTransfer !== null) {
            $fileLocalizedAttributesTransfer->setFkFile($fileTransfer->getIdFile());
        }

        $localeTransfer = $fileLocalizedAttributesTransfer->getLocale();
        if ($localeTransfer !== null) {
            $fileLocalizedAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
        }
    }
}
