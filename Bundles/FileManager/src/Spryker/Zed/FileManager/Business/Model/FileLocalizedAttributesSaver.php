<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

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
        $fkFile = $fileManagerDataTransfer->getFile()->getIdFile();

        foreach ($fileManagerDataTransfer->getFileLocalizedAttributes() as $fileLocalizedAttributesTransfer) {
            $this->prepareFileLocalizedAttributesTransfer($fileLocalizedAttributesTransfer, $fkFile);
            $this->entityManager->saveFileLocalizedAttribute($fileLocalizedAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer
     * @param int $fkFile
     *
     * @return void
     */
    protected function prepareFileLocalizedAttributesTransfer(FileLocalizedAttributesTransfer $fileLocalizedAttributesTransfer, int $fkFile)
    {
        $fileLocalizedAttributesTransfer->setFkFile($fkFile);
        $fileLocalizedAttributesTransfer->setFkLocale(
            $fileLocalizedAttributesTransfer->getLocale()->getIdLocale()
        );
    }
}
