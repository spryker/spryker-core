<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceFactory getFactory()
 */
class FlysystemService extends AbstractService implements FlysystemServiceInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceMetadataTransfer
     */
    public function getMetadata($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->getMetadata($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getMimeType($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->getMimeType($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function getTimestamp($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->getTimestamp($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return int
     */
    public function getSize($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->getSize($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function isPrivate($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->isPrivate($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return bool
     */
    public function has($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->has($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read($filesystemName, $path)
    {
        return $this->getFactory()
            ->createReader()
            ->read($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $directory
     * @param bool $recursive
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceTransfer[]
     */
    public function listContents($filesystemName, $directory = '', $recursive = false)
    {
        return $this->getFactory()
            ->createReader()
            ->listContents($filesystemName, $directory, $recursive);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function markAsPrivate($filesystemName, $path)
    {
        return $this->getFactory()
            ->createWriter()
            ->markAsPrivate($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function markAsPublic($filesystemName, $path)
    {
        return $this->getFactory()
            ->createWriter()
            ->markAsPublic($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $dirname
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function createDir($filesystemName, $dirname, array $config = [])
    {
        return $this->getFactory()
            ->createWriter()
            ->createDir($filesystemName, $dirname, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $dirname
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function deleteDir($filesystemName, $dirname)
    {
        return $this->getFactory()
            ->createWriter()
            ->deleteDir($filesystemName, $dirname);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function copy($filesystemName, $path, $newpath)
    {
        return $this->getFactory()
            ->createWriter()
            ->copy($filesystemName, $path, $newpath);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function delete($filesystemName, $path)
    {
        return $this->getFactory()
            ->createWriter()
            ->delete($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function put($filesystemName, $path, $content, array $config = [])
    {
        return $this->getFactory()
            ->createWriter()
            ->put($filesystemName, $path, $content, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $newpath
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function rename($filesystemName, $path, $newpath)
    {
        return $this->getFactory()
            ->createWriter()
            ->rename($filesystemName, $path, $newpath);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function update($filesystemName, $path, $content, array $config = [])
    {
        return $this->getFactory()
            ->createWriter()
            ->update($filesystemName, $path, $content, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return bool
     */
    public function write($filesystemName, $path, $content, array $config = [])
    {
        return $this->getFactory()
            ->createWriter()
            ->write($filesystemName, $path, $content, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return bool
     */
    public function putStream($filesystemName, $path, $resource, array $config = [])
    {
        return $this->getFactory()
            ->createStream()
            ->putStream($filesystemName, $path, $resource, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream($filesystemName, $path)
    {
        return $this->getFactory()
            ->createStream()
            ->readStream($filesystemName, $path);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return bool
     */
    public function updateStream($filesystemName, $path, $resource, array $config = [])
    {
        return $this->getFactory()
            ->createStream()
            ->updateStream($filesystemName, $path, $resource, $config);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return bool
     */
    public function writeStream($filesystemName, $path, $resource, array $config = [])
    {
        return $this->getFactory()
            ->createStream()
            ->writeStream($filesystemName, $path, $resource, $config);
    }

}
