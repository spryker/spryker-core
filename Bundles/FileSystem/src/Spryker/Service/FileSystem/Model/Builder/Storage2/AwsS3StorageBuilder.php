<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Builder\Storage;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\FileSystemStorage;

class AwsS3StorageBuilder extends AbstractStorageBuilder
{

    const KEY = 'key';
    const SECRET = 'secret';
    const REGION = 'region';
    const VERSION = 'version';
    const CREDENTIALS = 'credentials';

    /**
     * @var \Aws\S3\S3Client
     */
    protected $client;

    /**
     * @var \League\Flysystem\AwsS3v3\AwsS3Adapter
     */
    protected $adapter;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer
     */
    protected $config;

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface
     *
     * Sample config
     * 'key' => ' key',
     * 'secret' => 'secret',
     * 'bucket' => 'bucket',
     * 'version' => 'version',
     * 'region' => 'region',
     */
    protected function build()
    {
        $this
            ->buildS3Client()
            ->buildAdapter()
            ->buildFlySystem();

        return new FileSystemStorage($this->config, $this->filesystem);
    }

    /**
     * @return $this
     */
    protected function buildS3Client()
    {
        $this->client = new S3Client([
            self::CREDENTIALS => [
                self::KEY => $this->config->getKey(),
                self::SECRET => $this->config->getSecret(),
            ],
            self::REGION => $this->config->getRegion(),
            self::VERSION => $this->config->getVersion(),
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildAdapter()
    {
        $this->adapter = new AwsS3Adapter($this->client, $this->config->getBucket());

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildFlySystem()
    {
        $this->filesystem = new Filesystem($this->adapter);

        return $this;
    }

}
