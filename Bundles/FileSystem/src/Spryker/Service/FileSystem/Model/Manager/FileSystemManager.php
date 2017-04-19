<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Manager;

use Spryker\Service\FileSystem\Model\Exception\FileSystemStorageNotFoundException;

class FileSystemManager implements FileSystemManagerInterface
{

    /**
     * @var \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface[]
     */
    protected $storageCollection = [];

    /**
     * @param \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface[] $storageCollection
     */
    public function __construct(array $storageCollection)
    {
        $this->storageCollection = $storageCollection;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\FileSystem\Model\Exception\FileSystemStorageNotFoundException
     *
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
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
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface[]
     */
    public function getStorageCollection()
    {
        return $this->storageCollection;
    }

}
