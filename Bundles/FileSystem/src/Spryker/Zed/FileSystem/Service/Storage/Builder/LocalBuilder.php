<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Storage\Builder;

use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Filesystem;
use Spryker\Zed\FileSystem\Service\Storage\AbstractBuilder;
use Spryker\Zed\FileSystem\Service\Storage\FileSystemStorage;

class LocalBuilder extends AbstractBuilder
{

    const PATH = 'path';

    /**
     * @var array
     */
    protected $builderMandatoryConfigFields = [
        self::PATH,
    ];

    /**
     * Sample config
     * 'title' => 'Customer Data',
     * 'path' => 'customer/',
     * 'icon' => 'fa fa-archive',
     *
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    protected function buildStorage()
    {
        $path = sprintf('%s%s%s', $this->config[self::ROOT], DIRECTORY_SEPARATOR, $this->config[self::PATH]);
        $adapter = new LocalAdapter($path, LOCK_EX, LocalAdapter::DISALLOW_LINKS);
        $fileSystem = new Filesystem($adapter);

        if (!$fileSystem->has('/')) {
            $fileSystem->createDir('/');
        }

        return new FileSystemStorage($this->config, $fileSystem);
    }

}
