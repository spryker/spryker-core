<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Model;

interface FileContentInterface
{
    /**
     * @param string $fileName
     * @param string $content
     *
     * @return void
     */
    public function save(string $fileName, string $content);

    /**
     * @param string $fileName
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemWriteException
     *
     * @return void
     */
    public function delete(string $fileName);

    /**
     * @param string $fileName
     *
     * @throws \Spryker\Service\FileSystem\Dependency\Exception\FileSystemReadException
     *
     * @return string
     */
    public function read(string $fileName);
}
