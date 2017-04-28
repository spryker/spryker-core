<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Flysystem\Stub;

use Codeception\Configuration;
use Flysystem\Stub\FlysystemLocalFileSystem\FlysystemLocalFilesystemBuilderPluginStub;
use Functional\Spryker\Service\Flysystem\FlysystemServiceTest;
use Spryker\Service\Flysystem\FlysystemConfig as SprykerFlysystemConfig;

class FlysystemConfigStub extends SprykerFlysystemConfig
{

    /**
     * @return array
     */
    public function getFilesystemConfig()
    {
        $testDataFlysystemRootDirectory = Configuration::dataDir() . FlysystemServiceTest::ROOT_DIRECTORY;

        return [
            FlysystemServiceTest::FILE_SYSTEM_PRODUCT_IMAGE => [
                'sprykerAdapterClass' => FlysystemLocalFilesystemBuilderPluginStub::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FlysystemServiceTest::PATH_PRODUCT_IMAGE,
            ],
            FlysystemServiceTest::FILE_SYSTEM_DOCUMENT => [
                'sprykerAdapterClass' => FlysystemLocalFilesystemBuilderPluginStub::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FlysystemServiceTest::PATH_DOCUMENT,
            ],
        ];
    }

}
