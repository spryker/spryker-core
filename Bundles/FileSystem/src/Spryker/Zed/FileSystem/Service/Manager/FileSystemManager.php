<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Manager;

use Spryker\Zed\FileSystem\Service\Exception\FileSystemStorageNotFoundException;

class FileSystemManager implements FileSystemManagerInterface
{

    /**
     * @var \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface[]
     */
    protected $storageCollection = [];

    /**
     * @param \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface[] $storageCollection
     */
    public function __construct(array $storageCollection)
    {
        $this->storageCollection = $storageCollection;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\FileSystem\Service\Exception\FileSystemStorageNotFoundException
     *
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    public function getStorageByName($name)
    {
        if (!array_key_exists($name, $this->storageCollection)) {
            throw new FileSystemStorageNotFoundException(
                sprintf('FileSystemStorage "%s" was not found', $name)
            );
        }

        return $this->storageCollection[$name];
    }

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface[]
     */
    public function getStorageCollection()
    {
        return $this->storageCollection;
    }

}
