<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage\Builder;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Storage\AbstractBuilder;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;

class AwsS3Builder extends AbstractBuilder
{

    const KEY = 'key';
    const SECRET = 'secret';
    const BUCKET = 'bucket';
    const REGION = 'region';
    const VERSION = 'version';
    const CREDENTIALS = 'credentials';

    /**
     * @var array
     */
    protected $builderMandatoryConfigFields = [
        self::KEY,
        self::SECRET,
        self::BUCKET,
        self::REGION,
        self::VERSION,
    ];

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     *
     * Sample config
     * 'title' => 'Invoices',
     * 'icon' => 'fa fa-archive',
     * 'key' => ' key',
     * 'secret' => 'secret',
     * 'bucket' => 'bucket',
     * 'version' => 'version',
     * 'region' => 'region',
     */
    protected function buildStorage()
    {
        $client = new S3Client([
            self::CREDENTIALS => [
                self::KEY => $this->config[self::KEY],
                self::SECRET => $this->config[self::SECRET],
            ],
            self::REGION => $this->config[self::REGION],
            self::VERSION => $this->config[self::VERSION],
        ]);

        $adapter = new AwsS3Adapter($client, $this->config[self::BUCKET]);
        $fileSystem = new Filesystem($adapter);

        return new FileSystemStorage($this->config, $fileSystem);
    }

}
