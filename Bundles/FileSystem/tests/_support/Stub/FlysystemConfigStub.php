<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

use Codeception\Configuration;
use Functional\Spryker\Zed\FileSystem\Business\FileSystemFacadeTest;
use Spryker\Service\Flysystem\FlysystemConfig as SprykerFlysystemConfig;
use Spryker\Service\Flysystem\Model\Builder\LocalBuilder;

class FlysystemConfigStub extends SprykerFlysystemConfig
{

    /**
     * @return array
     */
    public function getFilesystemConfig()
    {
        $testDataFlysystemRootDirectory = Configuration::dataDir() . FileSystemFacadeTest::ROOT_DIRECTORY;

        return [
            FileSystemFacadeTest::FILE_SYSTEM_PRODUCT_IMAGE => [
                'type' => LocalBuilder::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemFacadeTest::PATH_PRODUCT_IMAGE,
            ],
            FileSystemFacadeTest::FILE_SYSTEM_DOCUMENT => [
                'type' => LocalBuilder::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FileSystemFacadeTest::PATH_DOCUMENT,
            ],
        ];
    }

}
