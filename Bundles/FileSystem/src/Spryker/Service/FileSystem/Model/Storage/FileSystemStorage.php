<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage;

use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidFilenameException;
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
     * @param array $nameTokens
     *
     * @return string
     */
    public function generateValidName(array $nameTokens)
    {
        array_walk($nameTokens, function (&$item) {
            $item = str_replace('/', '', $item);
        });

        $name = $this->generateValidPath($nameTokens);
        $this->validateName($name);

        return $name;
    }

    /**
     * @param array $pathTokens
     *
     * @return string
     */
    public function generateValidPath(array $pathTokens)
    {
        $name = implode(DIRECTORY_SEPARATOR, $pathTokens);
        $name = str_replace('//', '/', $name);

        return $name;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemInvalidFilenameException
     *
     * @return void
     */
    public function validateName($name)
    {
        $name = trim($name);

        $invalidNames = ['', '/', '\\'];
        if (in_array($name, $invalidNames)) {
            throw new FileSystemInvalidFilenameException($name);
        }
    }

}
