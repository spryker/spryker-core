<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

use Exception;
use Generated\Shared\Transfer\FlysystemResourceMetadataTransfer;
use Generated\Shared\Transfer\FlysystemResourceTransfer;
use League\Flysystem\AdapterInterface;
use Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException;
use Spryker\Service\Flysystem\Exception\MetadataNotFoundException;
use Spryker\Service\Flysystem\Model\Provider\FilesystemProviderInterface;
use Spryker\Shared\Flysystem\OperationHandler\ReadOperationHandlerTrait;
use Throwable;

class Reader implements ReaderInterface
{
    use ReadOperationHandlerTrait;

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
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function isPrivate($filesystemName, $path)
    {
        try {
            $visibility = $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->getVisibility($path);

            return $visibility === AdapterInterface::VISIBILITY_PRIVATE;
        } catch (Exception $exception) {
            throw new FileSystemReadException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (Throwable $exception) {
            throw new FileSystemReadException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function has($filesystemName, $path)
    {
        try {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->has($path);
        } catch (Exception $exception) {
            throw new FileSystemReadException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (Throwable $exception) {
            throw new FileSystemReadException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceMetadataTransfer
     */
    public function getMetadata($filesystemName, $path)
    {
        return $this->handleReadOperation(function () use ($filesystemName, $path) {
            $metadata = $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->getMetadata($path);

            if (!$metadata) {
                throw new MetadataNotFoundException(sprintf(
                    'No metadata found for filesystem "%s" in path "%s"',
                    $filesystemName,
                    $path
                ));
            }

            $metadataTransfer = new FlysystemResourceMetadataTransfer();
            $metadataTransfer->fromArray($metadata, true);

            $isFile = $this->isFile($metadataTransfer->getType());
            $metadataTransfer->setIsFile($isFile);

            return $metadataTransfer;
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string
     */
    public function getMimeType($filesystemName, $path)
    {
        return $this->handleReadOperation(function () use ($filesystemName, $path) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->getMimetype($path);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string
     */
    public function getVisibility($filesystemName, $path)
    {
        return $this->handleReadOperation(function () use ($filesystemName, $path) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->getVisibility($path);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|null
     */
    public function getTimestamp($filesystemName, $path)
    {
        return $this->handleReadOperation(function () use ($filesystemName, $path) {
            $timestamp = $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->getTimestamp($path);

            return $timestamp ? (int)$timestamp : null;
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return int
     */
    public function getSize($filesystemName, $path)
    {
        return $this->handleReadOperation(function () use ($filesystemName, $path) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->getSize($path);
        });
    }

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return string
     */
    public function read($filesystemName, $path)
    {
        return $this->handleReadOperation(function () use ($filesystemName, $path) {
            return $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->read($path);
        });
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
        return $this->handleReadOperation(function () use ($filesystemName, $directory, $recursive) {
            $resourceCollection = $this->filesystemProvider
                ->getFilesystemByName($filesystemName)
                ->listContents($directory, $recursive);

            $results = [];
            foreach ($resourceCollection as $resource) {
                $resourceTransfer = new FlysystemResourceTransfer();
                $resourceTransfer->fromArray($resource, true);

                $isFile = $this->isFile($resourceTransfer->getType());
                $resourceTransfer->setIsFile($isFile);

                $results[] = $resourceTransfer;
            }

            return $results;
        });
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
