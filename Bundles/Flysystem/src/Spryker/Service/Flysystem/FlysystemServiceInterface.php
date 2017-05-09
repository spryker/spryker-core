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
     * @param string $filesystemName
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceMetadataTransfer|null
     */
    public function getMetadata($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource mime type
     * - Return resource mime type, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getMimeType($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource timestamp
     * - Return resource timestamp, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getTimestamp($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Get resource size
     * - Return resource size, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return int|false
     */
    public function getSize($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Check if resource has private access rights
     * - Return true if resource has private access rights
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function isPrivate($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Mark resource with private access rights
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function markAsPrivate($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Mark resource with public access rights
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function markAsPublic($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create directory with its path
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $dirname
     * @param array $config
     *
     * @return bool
     */
    public function createDir($filesystemName, $dirname, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Delete empty directory
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($filesystemName, $dirname);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Copy file, the destination must not exist
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($filesystemName, $path, $newpath);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Delete file
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function delete($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create a file or update if exists
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function put($filesystemName, $path, $content, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Read file
     * - Return file content, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return false|string
     */
    public function read($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create a file or update if exists
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $newpath
     * @param string $path
     *
     * @return string|false
     */
    public function rename($filesystemName, $path, $newpath);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Update an existing file
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function update($filesystemName, $path, $content, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Write a new file
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function write($filesystemName, $path, $content, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Create a file or update if exists using stream
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @return bool
     */
    public function putStream($filesystemName, $path, $resource, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Retrieve stream for a file
     * - Return a read-stream for the path, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return mixed|false
     */
    public function readStream($filesystemName, $path);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Update an existing file using a stream
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @return bool
     */
    public function updateStream($filesystemName, $path, $resource, array $config = []);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Write a new file using a stream
     * - Return true on success, false on failure
     *
     * @param string $filesystemName
     * @param string $path
     * @param mixed $resource
     * @param array $config
     *
     * @return bool
     */
    public function writeStream($filesystemName, $path, $resource, array $config = []);

    /**
     * Specification
     * - Select pre-configured filesystem
     * - List contents under a path
     * - Return array of FileSystemResourceTransfer objects located under given path
     *
     * @param string $filesystemName
     * @param string $directory
     * @param bool $recursive
     *
     * @return \Generated\Shared\Transfer\FlysystemResourceTransfer[]
     */
    public function listContents($filesystemName, $directory = '', $recursive = false);

    /**
     * Specification:
     * - Select pre-configured filesystem
     * - Check if resource exists
     * - Return true if resource exist, false otherwise
     *
     * @param string $filesystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($filesystemName, $path);

}
