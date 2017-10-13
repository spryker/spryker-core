<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem\Model;

interface StreamInterface
{
    /**
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
     * @param string $filesystemName
     * @param string $path
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream($filesystemName, $path);

    /**
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
