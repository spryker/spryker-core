<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

use Codeception\Configuration;
use Functional\Spryker\Service\FileSystem\FileSystemServiceTest;
use Spryker\Service\FileSystem\FileSystemConfig as SprykerFileSystemConfig;
use Spryker\Service\FileSystem\Model\Storage\Builder\LocalStorageBuilder;

class FileSystemConfigStub extends SprykerFileSystemConfig
{

    /**
     * @return array
     */
    public function getStorageConfig()
    {
        $testDataFileSystemRootDirectory = Configuration::dataDir() . FileSystemServiceTest::ROOT_DIRECTORY;

        return [
            FileSystemServiceTest::STORAGE_PRODUCT_IMAGE => [
                'type' => LocalStorageBuilder::class,
                'root' => $testDataFileSystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_STORAGE_PRODUCT_IMAGE,
            ],
            FileSystemServiceTest::STORAGE_DOCUMENT => [
                'type' => LocalStorageBuilder::class,
                'root' => $testDataFileSystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_STORAGE_DOCUMENT,
            ],
        ];
    }

}
