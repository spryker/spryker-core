<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

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
        return [
            FileSystemServiceTest::STORAGE_PRODUCT => [
                'type' => LocalStorageBuilder::class,
                'root' => FileSystemServiceTest::ROOT_DIRECTORY,
                'path' => FileSystemServiceTest::PATH_STORAGE_PRODUCT,
            ],
            FileSystemServiceTest::STORAGE_CUSTOMER => [
                'type' => LocalStorageBuilder::class,
                'root' => FileSystemServiceTest::ROOT_DIRECTORY,
                'path' => FileSystemServiceTest::PATH_STORAGE_CUSTOMER,
            ],
        ];
    }

}
