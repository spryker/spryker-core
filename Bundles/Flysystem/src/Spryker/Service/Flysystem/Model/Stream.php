<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

use Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface;
use Spryker\Shared\Flysystem\OperationHandler\StreamOperationHandlerTrait;

class Stream implements StreamInterface
{
    use StreamOperationHandlerTrait;

    /**
     * @var \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface
     */
    protected $filesystemProvider;

    /**
     * @param \Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface $filesystemProvider
     */
    public function __construct(FilesystemProviderInterface $filesystemProvider)
    {
        $this->filesystemProvider = $filesystemProvider;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @return void
     */
    public function putStream($filesystemName, $path, $resource, array $config = [])
    {
        $this->handleStreamOperation(function () use ($filesystemName, $path, $resource, $config) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->putStream($path, $resource, $config);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return mixed
     */
    public function readStream($filesystemName, $path)
    {
        return $this->handleStreamOperation(function () use ($filesystemName, $path) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->readStream($path);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @return void
     */
    public function updateStream($filesystemName, $path, $resource, array $config = [])
    {
        $this->handleStreamOperation(function () use ($filesystemName, $path, $resource, $config) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->updateStream($path, $resource, $config);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @return void
     */
    public function writeStream($filesystemName, $path, $resource, array $config = [])
    {
        $this->handleStreamOperation(function () use ($filesystemName, $path, $resource, $config) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->writeStream($path, $resource, $config);
        });
    }
}
