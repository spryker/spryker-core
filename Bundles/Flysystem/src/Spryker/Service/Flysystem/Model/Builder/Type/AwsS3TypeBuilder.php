<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model\Builder\Type;

use Aws\S3\S3Client;
use Generated\Shared\Transfer\FlysystemConfigAwsTransfer;
use Generated\Shared\Transfer\FlysystemConfigTransfer;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Spryker\Service\Flysystem\Model\Builder\FilesystemBuilderInterface;

class AwsS3TypeBuilder implements FilesystemBuilderInterface
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
     * @var \Generated\Shared\Transfer\FlysystemConfigTransfer
     */
    protected $fileSystemConfig;

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigAwsTransfer
     */
    protected $adapterConfig;

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigTransfer $fileSystemConfig
     * @param \Generated\Shared\Transfer\FlysystemConfigAwsTransfer $adapterConfig
     */
    public function __construct(
        FlysystemConfigTransfer $fileSystemConfig,
        FlysystemConfigAwsTransfer $adapterConfig
    ) {
        $this->fileSystemConfig = $fileSystemConfig;
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * @return \League\Flysystem\Filesystem
     */
    public function build()
    {
        $this
            ->buildS3Client()
            ->buildAdapter()
            ->buildFilesystem()
            ->buildPlugins();

        return $this->filesystem;
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
    protected function buildFilesystem()
    {
        $this->filesystem = new Filesystem($this->adapter);

        return $this;
    }

    /**
     * @return $this
     */
    protected function buildPlugins()
    {
        return $this;
    }

}
