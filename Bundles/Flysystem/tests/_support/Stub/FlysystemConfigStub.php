<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Flysystem\Stub;

use Codeception\Configuration;
use Functional\Spryker\Service\Flysystem\FlysystemServiceTest;
use Spryker\Service\Flysystem\FlysystemConfig as SprykerFlysystemConfig;
use Spryker\Service\Flysystem\Model\Builder\Storage\LocalStorageBuilder;
use Spryker\Shared\Flysystem\FlysystemConstants;

class FlysystemConfigStub extends SprykerFlysystemConfig
{

    /**
     * @return array
     */
    public function getStorageConfig()
    {
        $testDataFlysystemRootDirectory = Configuration::dataDir() . FlysystemServiceTest::ROOT_DIRECTORY;

        return [
            FlysystemServiceTest::STORAGE_PRODUCT_IMAGE => [
                'type' => LocalStorageBuilder::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FlysystemServiceTest::PATH_STORAGE_PRODUCT_IMAGE,
            ],
            FlysystemServiceTest::STORAGE_DOCUMENT => [
                'type' => LocalStorageBuilder::class,
                'root' => $testDataFlysystemRootDirectory,
                'path' => FlysystemServiceTest::PATH_STORAGE_DOCUMENT,
            ],
        ];
    }

}
