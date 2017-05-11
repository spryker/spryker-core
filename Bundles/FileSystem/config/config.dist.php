<?php
/**
 * Copy over the following configs to your config
 */

use Spryker\Service\FlysystemAws3v3FileSystem\Plugin\Flysystem\Aws3v3FilesystemBuilderPlugin;
use Spryker\Service\FlysystemFtpFileSystem\Plugin\Flysystem\FtpFilesystemBuilderPlugin;

use Spryker\Service\FlysystemLocalFileSystem\Plugin\Flysystem\LocalFilesystemBuilderPlugin;
use Spryker\Shared\FileSystem\FileSystemConstants;

$config[FileSystemConstants::FILESYSTEM_SERVICE] = [
    'media' => [
        'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
        'root' => APPLICATION_ROOT_DIR . '/data/DE/media/',
        'path' => 'images/categories/',
    ],
    'customer' => [
        'sprykerAdapterClass' => LocalFilesystemBuilderPlugin::class,
        'root' => APPLICATION_ROOT_DIR . '/data/DE/customer_storage/',
        'path' => 'documents/',
    ],
];

$config[FileSystemConstants::FILESYSTEM_SERVICE] = [
    'media' => [
        'sprykerAdapterClass' => Aws3v3FilesystemBuilderPlugin::class,
        'root' => '/DE/',
        'path' => 'media/',
        'key' => '..',
        'secret' => '..',
        'bucket' => '..',
        'version' => '..',
        'region' => '..',
    ],
    'customer' => [
        'sprykerAdapterClass' => FtpFilesystemBuilderPlugin::class,
        'host' => '..',
        'username' => '..',
        'password' => '..',
    ],
];
