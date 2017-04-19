<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileSystem\Service\FileSystemServiceFactory getDependencyContainer()
 */
class FileSystemFacade extends AbstractFacade
{

    /**
     * @param string $name
     *
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    public function getStorageByName($name)
    {
        return $this->getDependencyContainer()
            ->createFileSystemManager()
            ->getStorageByName($name);
    }

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface[]
     */
    public function getStorageCollection()
    {
        return $this->getDependencyContainer()
            ->createFileSystemManager()
            ->getStorageCollection();
    }

    /**
     * @param array $data
     *
     * @return \Spryker\Zed\FileSystem\Service\Flysystem\ResourceInterface
     */
    public function getFlySystemResource(array $data)
    {
        return $this->getDependencyContainer()
            ->createFlysystemResource($data);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getMimeTypeByFilename($filename)
    {
        return $this->getDependencyContainer()
            ->createMimeTypeManager()
            ->getMimeTypeByFilename($filename);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getExtensionByFilename($filename)
    {
        return $this->getDependencyContainer()
            ->createMimeTypeManager()
            ->getExtensionByFilename($filename);
    }

}
