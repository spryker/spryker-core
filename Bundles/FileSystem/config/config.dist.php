<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Service\Flysystem\Model\Builder\Filesystem\LocalFilesystemBuilder;

$config[FileSystemConstants::FILESYSTEM_STORAGE] = [
    FileSystemConstants::FILESYSTEM_SERVICE => [
        'productCategoryImage' => [
            'type' => LocalFilesystemBuilder::class,
            'root' => APPLICATION_ROOT_DIR . '/data/DE/media/',
            'path' => 'images/categories/',
        ],
        'customerDocument' => [
            'type' => LocalFilesystemBuilder::class,
            'root' => APPLICATION_ROOT_DIR . '/data/DE/customer_storage/',
            'path' => 'documents/',
        ],
    ],
];
