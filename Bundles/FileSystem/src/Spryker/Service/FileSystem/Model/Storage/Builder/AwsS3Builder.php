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
use Spryker\Service\FileSystem\Model\Storage\AbstractBuilder;
use Spryker\Service\FileSystem\Model\Storage\FileSystemStorage;

class AwsS3Builder extends AbstractBuilder
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
        $configTransfer->fromArray($this->configTransfer->getData(), true);

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

        $client = new S3Client([
            self::CREDENTIALS => [
                self::KEY => $storageConfigTransfer->getKey(),
                self::SECRET => $storageConfigTransfer->getSecret(),
            ],
            self::REGION => $storageConfigTransfer->getRegion(),
            self::VERSION => $storageConfigTransfer->getVersion(),
        ]);

        $adapter = new AwsS3Adapter($client, $storageConfigTransfer->getBucket());
        $fileSystem = new Filesystem($adapter);

        return new FileSystemStorage($this->configTransfer, $fileSystem);
    }

    /**
     * @return void
     */
    protected function validateConfig()
    {
        $storageConfigTransfer = $this->buildStorageConfig();

        $storageConfigTransfer->requireKey();
        $storageConfigTransfer->requireSecret();
        $storageConfigTransfer->requireBucket();
        $storageConfigTransfer->requireVersion();
        $storageConfigTransfer->requireRegion();
    }

}
