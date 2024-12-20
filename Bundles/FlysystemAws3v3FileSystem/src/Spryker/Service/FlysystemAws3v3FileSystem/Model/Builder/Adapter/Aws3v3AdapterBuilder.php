<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemAws3v3FileSystem\Model\Builder\Adapter;

use Aws\S3\S3Client;
use Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\FilesystemAdapter;
use Spryker\Service\FlysystemAws3v3FileSystem\Exception\NoBucketException;

class Aws3v3AdapterBuilder implements AdapterBuilderInterface
{
    /**
     * @var string
     */
    public const KEY = 'key';

    /**
     * @var string
     */
    public const SECRET = 'secret';

    /**
     * @var string
     */
    public const REGION = 'region';

    /**
     * @var string
     */
    public const CREDENTIALS = 'credentials';

    /**
     * @var string
     */
    protected const ENDPOINT = 'endpoint';

    /**
     * @var \League\Flysystem\FilesystemAdapter
     */
    protected $adapter;

    /**
     * @var \Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer
     */
    protected $adapterConfig;

    /**
     * @var \Aws\S3\S3Client
     */
    protected $client;

    /**
     * @param \Generated\Shared\Transfer\FlysystemConfigAws3v3Transfer $adapterConfig
     */
    public function __construct(FlysystemConfigAws3v3Transfer $adapterConfig)
    {
        $this->adapterConfig = $adapterConfig;
    }

    /**
     * @return \League\Flysystem\FilesystemAdapter
     */
    public function build(): FilesystemAdapter
    {
        $this->buildS3Client()
            ->buildAdapter();

        return $this->adapter;
    }

    /**
     * @return $this
     */
    protected function buildS3Client()
    {
        $this->client = new S3Client([
            static::CREDENTIALS => [
                static::KEY => $this->adapterConfig->getKey(),
                static::SECRET => $this->adapterConfig->getSecret(),
            ],
            static::REGION => $this->adapterConfig->getRegion(),
            static::ENDPOINT => $this->adapterConfig->getEndpoint(),
        ]);

        return $this;
    }

    /**
     * @throws \Spryker\Service\FlysystemAws3v3FileSystem\Exception\NoBucketException
     *
     * @return $this
     */
    protected function buildAdapter()
    {
        $bucket = $this->adapterConfig->getBucket();

        if ($bucket === null) {
            throw new NoBucketException('Bucket not set in adapter configuration.');
        }

        $this->adapter = new AwsS3V3Adapter(
            $this->client,
            $bucket,
            $this->adapterConfig->getPath() ?? '',
            null,
            null,
            [],
            $this->adapterConfig->getIsStreamReads() ?? false,
        );

        return $this;
    }
}
