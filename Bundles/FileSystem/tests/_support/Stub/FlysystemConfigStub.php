<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

use Codeception\Configuration;
use Functional\Spryker\Service\FileSystem\FileSystemServiceTest;
use Spryker\Service\Flysystem\FlysystemConfig as SprykerFlysystemConfig;
use Spryker\Service\Flysystem\Model\Builder\Filesystem\LocalFilesystemBuilder;

class FlysystemConfigStub extends SprykerFlysystemConfig
{

    /**
     * @return array
     */
    public function getFilesystemConfig()
    {
        $testDataFlysystemRootDirectory = Configuration::dataDir() . FileSystemServiceTest::ROOT_DIRECTORY;

        return [
            FileSystemServiceTest::FILE_SYSTEM_PRODUCT_IMAGE => [
                'type' => LocalFilesystemBuilder::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_PRODUCT_IMAGE,
            ],
            FileSystemServiceTest::FILE_SYSTEM_DOCUMENT => [
                'type' => LocalFilesystemBuilder::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemServiceTest::PATH_DOCUMENT,
            ],
        ];
    }

}
