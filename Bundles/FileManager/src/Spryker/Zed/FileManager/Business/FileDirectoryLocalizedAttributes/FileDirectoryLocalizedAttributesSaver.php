<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\FileDirectoryLocalizedAttributes;

use Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\FileDirectoryTransfer;
use Spryker\Zed\FileManager\Persistence\FileManagerEntityManagerInterface;

class FileDirectoryLocalizedAttributesSaver implements FileDirectoryLocalizedAttributesSaverInterface
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
     * @param \Generated\Shared\Transfer\FileDirectoryTransfer $fileDirectoryTransfer
     *
     * @return void
     */
    public function save(FileDirectoryTransfer $fileDirectoryTransfer)
    {
        $fkFileDirectory = $fileDirectoryTransfer->getIdFileDirectory();

        foreach ($fileDirectoryTransfer->getFileDirectoryLocalizedAttributes() as $fileDirectoryLocalizedAttributesTransfer) {
            $this->prepareFileDirectoryLocalizedAttributesTransfer($fileDirectoryLocalizedAttributesTransfer, $fkFileDirectory);
            $this->entityManager->saveFileDirectoryLocalizedAttribute($fileDirectoryLocalizedAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer
     * @param int $fkFileDirectory
     *
     * @return void
     */
    protected function prepareFileDirectoryLocalizedAttributesTransfer(FileDirectoryLocalizedAttributesTransfer $fileDirectoryLocalizedAttributesTransfer, int $fkFileDirectory)
    {
        $fileDirectoryLocalizedAttributesTransfer->setFkFileDirectory($fkFileDirectory);
        $fileDirectoryLocalizedAttributesTransfer->setFkLocale(
            $fileDirectoryLocalizedAttributesTransfer->getLocale()->getIdLocale()
        );
    }
}
