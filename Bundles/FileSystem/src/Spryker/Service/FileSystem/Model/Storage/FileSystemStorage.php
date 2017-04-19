<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage;

use League\Flysystem\Filesystem;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class FileSystemStorage implements FileSystemStorageInterface
{

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigTransfer
     */
    protected $storageConfig;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $storageConfigTransfer
     * @param \League\Flysystem\Filesystem $fileSystem
     */
    public function __construct(AbstractTransfer $storageConfigTransfer, Filesystem $fileSystem)
    {
        $this->storageConfig = $storageConfigTransfer;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function getFileSystem()
    {
        return $this->fileSystem;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->storageConfig
            ->requireName()
            ->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->storageConfig
            ->requireType()
            ->getType();
    }

}
