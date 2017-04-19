<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Storage\Builder;

use Aws\S3\S3Client;
use Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Spryker\Service\FileSystem\Model\Storage\AbstractStorageBuilder;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;

class AwsS3StorageBuilder extends AbstractStorageBuilder
{

    const KEY = 'key';
    const SECRET = 'secret';
    const REGION = 'region';
    const VERSION = 'version';
    const CREDENTIALS = 'credentials';

    /**
     * @return \Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer
     */
    protected function buildStorageConfig()
    {
        $configTransfer = new FileSystemStorageConfigAwsTransfer();
        $configTransfer->fromArray($this->config->getData(), true);

        return $configTransfer;
    }

    /**
     * @return \Spryker\Service\FileSystem\Model\Storage\FileSystemStorageInterface
     *
     * Sample config
     * 'key' => ' key',
     * 'secret' => 'secret',
     * 'bucket' => 'bucket',
     * 'version' => 'version',
     * 'region' => 'region',
     */
    protected function buildStorage()
    {
        $storageConfigTransfer = $this->buildStorageConfig();
        $client = $this->buildS3Client($storageConfigTransfer);
        $adapter = $this->buildAdapter($client, $storageConfigTransfer);
        $fileSystem = $this->buildFlySystem($adapter);

        return new FileSystemStorage($this->config, $fileSystem);
    }

    /**
     * @return void
     */
    protected function validateStorageConfig()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $storageConfigTransfer->requireKey();
        $storageConfigTransfer->requireSecret();
        $storageConfigTransfer->requireBucket();
        $storageConfigTransfer->requireVersion();
        $storageConfigTransfer->requireRegion();
    }

    /**
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer $storageConfigTransfer
     *
     * @return \Aws\S3\S3Client
     */
    protected function buildS3Client(FileSystemStorageConfigAwsTransfer $storageConfigTransfer)
    {
        $client = new S3Client([
            self::CREDENTIALS => [
                self::KEY => $storageConfigTransfer->getKey(),
                self::SECRET => $storageConfigTransfer->getSecret(),
            ],
            self::REGION => $storageConfigTransfer->getRegion(),
            self::VERSION => $storageConfigTransfer->getVersion(),
        ]);

        return $client;
    }

    /**
     * @param \Aws\S3\S3Client $s3Client
     * @param \Generated\Shared\Transfer\FileSystemStorageConfigAwsTransfer $storageConfigTransfer
     *
     * @return \League\Flysystem\AwsS3v3\AwsS3Adapter
     */
    protected function buildAdapter(S3Client $s3Client, FileSystemStorageConfigAwsTransfer $storageConfigTransfer)
    {
        $adapter = new AwsS3Adapter($s3Client, $storageConfigTransfer->getBucket());

        return $adapter;
    }

    /**
     * @param \League\Flysystem\AwsS3v3\AwsS3Adapter $adapter
     *
     * @return \League\Flysystem\Filesystem
     */
    protected function buildFlySystem(AwsS3Adapter $adapter)
    {
        $fileSystem = new Filesystem($adapter);

        return $fileSystem;
    }

}
