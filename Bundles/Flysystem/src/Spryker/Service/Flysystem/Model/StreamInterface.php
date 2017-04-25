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
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function putStream($filesystemName, $path, $resource, array $config = []);

    /**
     * @param string $filesystemName
     * @param string $path
     *
     * @return resource|false
     */
    public function readStream($filesystemName, $path);

    /**
     * @param string $filesystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function updateStream($filesystemName, $path, $resource, array $config = []);

    /**
     * @param string $filesystemName
     * @param string $path
     * @param resource $resource
     * @param array $config
     *
     * @return bool
     */
    public function writeStream($filesystemName, $path, $resource, array $config = []);

}
