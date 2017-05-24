<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

interface WriterInterface
{

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPrivate($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function markAsPublic($filesystemName, $path);

    /**
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
     * @param string $filesystemName
     * @param string $dirname
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function deleteDir($filesystemName, $dirname);

    /**
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
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function delete($filesystemName, $path);

    /**
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

}
