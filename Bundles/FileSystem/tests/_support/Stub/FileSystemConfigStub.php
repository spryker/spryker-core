<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

use Codeception\Configuration;
use FileSystem\Stub\Flysystem\FlysystemLocalFilesystemBuilderPluginStub;
use Functional\Spryker\Service\FileSystem\FileSystemServiceTest;
use Spryker\Service\FileSystem\FileSystemConfig as SprykerFileSystemConfig;

class FileSystemConfigStub extends SprykerFileSystemConfig
{

    /**
     * @return array
     */
    public function getFilesystemConfig()
    {
        $testDataFlysystemRootDirectory = Configuration::dataDir() . FileSystemServiceTest::ROOT_DIRECTORY;

        return [
            FileSystemServiceTest::FILE_SYSTEM_PRODUCT_IMAGE => [
                'sprykerAdapterClass' => FlysystemLocalFilesystemBuilderPluginStub::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_PRODUCT_IMAGE,
            ],
            FileSystemServiceTest::FILE_SYSTEM_DOCUMENT => [
                'sprykerAdapterClass' => FlysystemLocalFilesystemBuilderPluginStub::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_DOCUMENT,
            ],
        ];
    }

}
