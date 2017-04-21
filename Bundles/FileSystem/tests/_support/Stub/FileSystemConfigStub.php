<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

use Codeception\Configuration;
use Functional\Spryker\Zed\FileSystem\Business\FileSystemFacadeTest;
use Spryker\Service\Flysystem\Model\Builder\LocalBuilder;
use Spryker\Zed\FileSystem\FileSystemConfig as SprykerFileSystemConfig;

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
                'type' => LocalBuilder::class,
                'root' => $testDataFileSystemRootDirectory,
                'path' => FileSystemFacadeTest::PATH_STORAGE_PRODUCT_IMAGE,
            ],
            FileSystemFacadeTest::STORAGE_DOCUMENT => [
                'type' => LocalBuilder::class,
                'root' => $testDataFileSystemRootDirectory,
                'path' => FileSystemFacadeTest::PATH_STORAGE_DOCUMENT,
            ],
        ];
    }

}
