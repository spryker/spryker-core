<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FileSystem\Stub;

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
            'productImage' => [
                'type' => LocalStorageBuilder::class,
                'root' => 'data/DE/uploads/',
                'path' => 'images/product/',
            ],
            'customerImage' => [
                'type' => LocalStorageBuilder::class,
                'root' => 'data/DE/uploads/',
                'path' => 'customer/',
            ],
        ];
    }

}
