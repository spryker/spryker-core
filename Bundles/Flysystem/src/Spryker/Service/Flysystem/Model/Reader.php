<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

use Generated\Shared\Transfer\FlysystemResourceMetadataTransfer;
use Generated\Shared\Transfer\FlysystemResourceTransfer;
use League\Flysystem\AdapterInterface;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface;

class Reader implements ReaderInterface
{

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
     *
     * @return bool
     */
    public function isPrivate($filesystemName, $path)
    {
        $visibility = $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->getVisibility($path);

        return $visibility === AdapterInterface::VISIBILITY_PRIVATE;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceMetadataTransfer|null
     */
    public function getMetadata($filesystemName, $path)
    {
        $metadata = $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->getMetadata($path);

        if (!$metadata) {
            return null;
        }

        $metadataTransfer = new FlysystemResourceMetadataTransfer();
        $metadataTransfer->fromArray($metadata, true);

        $isFile = $this->isFile($metadataTransfer->getType());
        $metadataTransfer->setIsFile($isFile);

        return $metadataTransfer;
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getMimeType($filesystemName, $path)
    {
        return $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->getMimetype($path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getVisibility($filesystemName, $path)
    {
        return $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->getVisibility($path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|false
     */
    public function getTimestamp($filesystemName, $path)
    {
        return $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->getTimestamp($path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|false
     */
    public function getSize($filesystemName, $path)
    {
        return $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->getSize($path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($filesystemName, $path)
    {
        return $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->has($path);
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function read($filesystemName, $path)
    {
        return $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->read($path);
    }

    /**
     * @param string $filesystemName
     * @param string $directory
     * @param bool $recursive
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceTransfer[]
     */
    public function listContents($filesystemName, $directory = '', $recursive = false)
    {
        $resourceCollection = $this->filesystemProvider
            ->getFilesystemByName($filesystemName)
            ->listContents($directory, $recursive);

        $results = [];
        foreach ($resourceCollection as $resource) {
            $resourceTransfer = new FlysystemResourceTransfer();
            $resourceTransfer->fromArray($resource);

            $isFile = $this->isFile($resourceTransfer->getType());
            $resourceTransfer->setIsFile($isFile);

            $results[] = $resourceTransfer;
        }

        return $results;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isFile($type)
    {
        return mb_strtolower($type) === mb_strtolower('file');
    }

}
