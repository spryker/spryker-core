<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\FileSystem\Stub;

use Codeception\Configuration;
use Spryker\Service\Flysystem\FlysystemConfig as SprykerFlysystemConfig;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use SprykerTest\Service\FileSystem\FileSystemServiceTest;

class FlysystemConfigStub extends SprykerFlysystemConfig
{
    /**
     * @return array
     */
    public function getFilesystemConfig(): array
    {
        $testDataFlysystemRootDirectory = Configuration::dataDir() . FileSystemServiceTest::ROOT_DIRECTORY;

        return [
            FileSystemServiceTest::FILE_SYSTEM_PRODUCT_IMAGE => [
                'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_PRODUCT_IMAGE,
            ],
            FileSystemServiceTest::FILE_SYSTEM_DOCUMENT => [
                'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_DOCUMENT,
            ],
        ];
    }
}
