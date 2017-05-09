<?php
/**
 * Copy over the following configs to your config
 */

/*use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;

$config[FileSystemConstants::FILESYSTEM_SERVICE] = [
    'productCategoryImage' => [
        'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
        'root' => APPLICATION_ROOT_DIR . '/data/DE/media/',
        'path' => 'images/categories/',
    ],
    'customerDocument' => [
        'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
        'root' => APPLICATION_ROOT_DIR . '/data/DE/customer_storage/',
        'path' => 'documents/',
    ],
];

use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem\Aws3v3FilesystemBuilderPlugin;

$config[FileSystemConstants::FILESYSTEM_SERVICE] = [
    'productCategoryImage' => [
        'sprykerAdapterClass' => Aws3v3FilesystemBuilderPlugin::class,
        'root' => '/DE/',
        'path' => 'media/',
        'key' => '..',
        'secret' => '..',
        'bucket' => '..',
        'version' => '..',
        'region' => '..',
    ],
    'customerDocument' => [
        'sprykerAdapterClass' => Aws3v3FilesystemBuilderPlugin::class,
        'root' => '/var/data/DE/',
        'path' => 'documents/',
    ],
];*/
