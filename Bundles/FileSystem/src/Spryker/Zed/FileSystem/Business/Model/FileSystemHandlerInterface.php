<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business\Model;

interface FileSystemHandlerInterface
{

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return \Generated\Shared\Transfer\FileSystemResourceMetadataTransfer|null
     */
    public function getMetadata($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getMimeType($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getVisibility($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $visibility 'public' or 'private'
     *
     * @return bool
     */
    public function setVisibility($fileSystemName, $path, $visibility);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getTimestamp($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function getSize($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $dirname
     * @param array $config
     *
     * @return string|false
     */
    public function createDir($fileSystemName, $dirname, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $dirname
     *
     * @return bool
     */
    public function deleteDir($fileSystemName, $dirname);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $newpath
     *
     * @return bool
     */
    public function copy($fileSystemName, $path, $newpath);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return bool
     */
    public function delete($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return bool
     */
    public function has($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function put($fileSystemName, $path, $content, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|false
     */
    public function read($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $newpath
     * @param string $path
     *
     * @return string|false
     */
    public function rename($fileSystemName, $path, $newpath);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function update($fileSystemName, $path, $content, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param string $content
     * @param array $config
     *
     * @return bool
     */
    public function write($fileSystemName, $path, $content, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return resource|false
     */
    public function putStream($fileSystemName, $path, $resource, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $path
     *
     * @return resource|false
     */
    public function readStream($fileSystemName, $path);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function updateStream($fileSystemName, $path, $resource, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function writeStream($fileSystemName, $path, $resource, array $config = []);

    /**
     * @param string $fileSystemName
     * @param string $directory
     * @param bool $recursive
     *
     * @return array
     */
    public function listContents($fileSystemName, $directory = '', $recursive = false);

}
