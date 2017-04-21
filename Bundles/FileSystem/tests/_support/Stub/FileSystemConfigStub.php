<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

use Codeception\Configuration;
use Functional\Spryker\Service\FileSystem\FileSystemFacadeTest;
use Spryker\Business\FileSystem\FileSystemConfig as SprykerFileSystemConfig;
use Spryker\Service\Flysystem\Model\Builder\Storage\LocalStorageBuilder;

class FileSystemConfigStub extends SprykerFileSystemConfig
{

    /**
     * @return array
     */
    public function getStorageConfig()
    {
        $testDataFileSystemRootDirectory = Configuration::dataDir() . FileSystemFacadeTest::ROOT_DIRECTORY;

        return [
            FileSystemFacadeTest::STORAGE_PRODUCT_IMAGE => [
                'type' => LocalStorageBuilder::class,
                'root' => $testDataFileSystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_STORAGE_PRODUCT_IMAGE,
            ],
            FileSystemFacadeTest::STORAGE_DOCUMENT => [
                'type' => LocalStorageBuilder::class,
                'root' => $testDataFileSystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_STORAGE_DOCUMENT,
            ],
        ];
    }

}
