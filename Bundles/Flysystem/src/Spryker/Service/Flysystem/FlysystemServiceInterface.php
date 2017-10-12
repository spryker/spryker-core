<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceFactory getFactory()
 */
interface FlysystemServiceInterface
{
    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource metadata
     * - Return resource metadata transfer, null on failure
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
    public function getMetadata($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource mime type
     * - Return resource mime type, throw exception on failure
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
    public function getMimeType($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource timestamp
     * - Return resource timestamp, throw exception on failure
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
    public function getTimestamp($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource size
     * - Return resource size, throw exception on failure
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
    public function getSize($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Check if resource has private access rights
     * - Return true if resource has private access rights
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
    public function isPrivate($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Check if resource exists
     * - Return true if resource exist, false otherwise
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
    public function has($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Read file
     * - Return file content, throw exception on failure
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
    public function read($filesystemName, $path);

    /**
     * Specification
     * - Select pre-configured filesystem
     * - List contents under a path
     * - Return array of FileSystemResourceTransfer objects located under given path
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
    public function listContents($filesystemName, $directory = '', $recursive = false);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Mark resource with private access rights
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPrivate($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Mark resource with public access rights
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPublic($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create directory with its path
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $dirname
     * @param array $config
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function createDir($filesystemName, $dirname, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Delete empty directory
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $dirname
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function deleteDir($filesystemName, $dirname);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Copy file, the destination must not exist
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function copy($filesystemName, $path, $newpath);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Delete file
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function delete($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create a file or update if exists
     * - Return true on success, throw exception on failure
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
     * @return void
     */
    public function put($filesystemName, $path, $content, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create a file or update if exists
     * - Return true on success, throw exception on failure
     *
     * @api
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function rename($filesystemName, $path, $newpath);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Update an existing file
     * - Return true on success, throw exception on failure
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
     * @return void
     */
    public function update($filesystemName, $path, $content, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Write a new file
     * - Return true on success, throw exception on failure
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
     * @return void
     */
    public function write($filesystemName, $path, $content, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create a file or update if exists using stream
     * - Return true on success, throw exception on failure
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
     * @return void
     */
    public function putStream($filesystemName, $path, $resource, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Retrieve stream for a file
     * - Return a read-stream for the path, throw exception on failure
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
    public function readStream($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Update an existing file using a stream
     * - Return true on success, throw exception on failure
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
     * @return void
     */
    public function updateStream($filesystemName, $path, $resource, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Write a new file using a stream
     * - Return true on success, throw exception on failure
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
     * @return void
     */
    public function writeStream($filesystemName, $path, $resource, array $config = []);
}
