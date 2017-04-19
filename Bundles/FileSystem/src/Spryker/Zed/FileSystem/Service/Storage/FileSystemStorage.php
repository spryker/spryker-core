<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Storage;

use League\Flysystem\Filesystem;
use Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidFilenameException;

class FileSystemStorage implements FileSystemStorageInterface
{

    const NAME = 'name';
    const TITLE = 'title';
    const ICON = 'icon';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $fileSystem;

    /**
     * @param array $config
     * @param \League\Flysystem\Filesystem $fileSystem
     */
    public function __construct(array $config, Filesystem $fileSystem)
    {
        $this->config = $config;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
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
        return $this->config[self::NAME];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->config[self::TITLE];
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->config[self::ICON];
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
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemInvalidFilenameException
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
