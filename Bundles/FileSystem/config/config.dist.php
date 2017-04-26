<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Shared\FileSystem\FileSystemConstants;

$config[FileSystemConstants::FILESYSTEM_STORAGE] = [
    FileSystemConstants::FILESYSTEM_SERVICE => [
        'productCategoryImage' => [
            'type' => Spryker\Service\Flysystem\Model\Builder\Filesystem\LocalFilesystemBuilder::class,
            'root' => APPLICATION_ROOT_DIR . '/data/DE/media/',
            'path' => 'images/categories/',
        ],
        'customerDocument' => [
            'type' => \Spryker\Service\Flysystem\Model\Builder\Filesystem\LocalFilesystemBuilder::class,
            'root' => APPLICATION_ROOT_DIR . '/data/DE/customer_storage/',
            'path' => 'documents/',
        ],
    ],
];
