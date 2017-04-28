<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Flysystem\Stub;

use Codeception\Configuration;
use Flysystem\Stub\FlysystemLocalFileSystem\Plugin\FlysystemLocalFilesystemBuilderPluginStub;
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
                'type' => FlysystemLocalFilesystemBuilderPluginStub::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FlysystemServiceTest::PATH_PRODUCT_IMAGE,
            ],
            FlysystemServiceTest::FILE_SYSTEM_DOCUMENT => [
                'type' => FlysystemLocalFilesystemBuilderPluginStub::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FlysystemServiceTest::PATH_DOCUMENT,
            ],
        ];
    }

}
