<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Storage\Flysystem;

use Aws\S3\S3Client;
use Generated\Shared\Transfer\FlysystemStorageConfigAwsTransfer;
use Generated\Shared\Transfer\FlysystemStorageConfigTransfer;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Model\Builder\FlysystemStorageBuilderInterface;
use Spryker\Service\Flysystem\Model\FlysystemStorage;

class AwsS3FlysystemBuilder implements FlysystemStorageBuilderInterface
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
     * @var \Generated\Shared\Transfer\FlysystemStorageConfigTransfer
     */
    protected $fileSystemConfig;

    /**
     * @var \Generated\Shared\Transfer\FlysystemStorageConfigAwsTransfer
     */
    protected $adapterConfig;

    /**
     * @param \Generated\Shared\Transfer\FlysystemStorageConfigTransfer $fileSystemConfig
     * @param \Generated\Shared\Transfer\FlysystemStorageConfigAwsTransfer $adapterConfig
     */
    public function __construct(
        FlysystemStorageConfigTransfer $fileSystemConfig,
        FlysystemStorageConfigAwsTransfer $adapterConfig
    ) {
        $this->fileSystemConfig = $fileSystemConfig;
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * Sample config
     * 'key' => ' key',
     * 'secret' => 'secret',
     * 'bucket' => 'bucket',
     * 'version' => 'version',
     * 'region' => 'region',
     *
     * @return \Spryker\Service\Flysystem\Model\FlysystemStorageInterface
     */
    public function build()
    {
        $this
            ->buildS3Client()
            ->buildAdapter()
            ->buildFlySystem();

        return new FlysystemStorage($this->fileSystemConfig, $this->filesystem);
    }

    /**
     * @return $this
     */
    protected function buildS3Client()
    {
        $this->client = new S3Client([
            self::CREDENTIALS => [
                self::KEY => $this->adapterConfig->getKey(),
                self::SECRET => $this->adapterConfig->getSecret(),
            ],
            self::REGION => $this->adapterConfig->getRegion(),
            self::VERSION => $this->adapterConfig->getVersion(),
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildAdapter()
    {
        $this->adapter = new AwsS3Adapter($this->client, $this->adapterConfig->getBucket());

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
