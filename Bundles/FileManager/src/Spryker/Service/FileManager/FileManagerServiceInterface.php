<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileManager;

interface FileManagerServiceInterface
{
    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getPublicUrl($fileName);

    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getZedUrl($fileName);

    /**
     * @param string $fileName
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     * @throws \Spryker\Service\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Generated\Shared\Transfer\FileManagerReadResponseTransfer
     */
    public function read($fileName);

    /**
     * @param string $fileName
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemStreamException
     *
     * @return mixed
     */
    public function readStream($fileName);
}
