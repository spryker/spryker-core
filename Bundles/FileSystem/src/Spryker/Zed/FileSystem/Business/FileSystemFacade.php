<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 * @method \Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory getFactory()
 */
class FileSystemFacade extends AbstractFacade implements FileSystemFacadeInterface
{

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $source
     * @param string $destination
     *
     * @return void
     */
    public function copy($fileSystemName, $source, $destination)
    {
    }

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|null|false
     */
    public function read($fileSystemName, $path)
    {
        return $this->getFactory()
            ->createFileSystemHandler()
            ->read($fileSystemName, $path);
    }

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $filename
     * @param string content
     *
     * @return void
     */
    public function write($fileSystemName, $filename, $content)
    {
    }

}
