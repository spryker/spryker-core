<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage\Builder;

use Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Storage\AbstractBuilder;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;

class LocalBuilder extends AbstractBuilder
{

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigLocalTransfer
     */
    protected function buildStorageConfig()
    {
        $configTransfer = new FileSystemStorageConfigLocalTransfer();
        $configTransfer->fromArray($this->configTransfer->getData(), true);

        return $configTransfer;
    }

    /**
     * Sample config
     * 'root' => '/data/uploads/',
     * 'path' => 'customers/pds/',
     *
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     */
    protected function buildStorage()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $path = sprintf(
            '%s%s%s',
            $storageConfigTransfer->getRoot(),
            DIRECTORY_SEPARATOR,
            $storageConfigTransfer->getPath()
        );

        $adapter = new LocalAdapter($path, LOCK_EX, LocalAdapter::DISALLOW_LINKS);
        $fileSystem = new Filesystem($adapter);

        if (!$fileSystem->has('/')) {
            $fileSystem->createDir('/');
        }

        return new FileSystemStorage($this->configTransfer, $fileSystem);
    }

    /**
     * @return void
     */
    protected function validateConfig()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $storageConfigTransfer->requirePath();
        $storageConfigTransfer->requireRoot();
    }

}
