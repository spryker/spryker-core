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
     * @param string $storageName
     * @param string $source
     * @param string $destination
     *
     * @return void
     */
    public function copy($storageName, $source, $destination)
    {
    }

    /**
     * @param string $filesystem
     * @param string $filename
     *
     * @return string|false The file contents or false on failure.
     */
    public function read($filesystem, $filename)
    {
    }

    /**
     * @param string $storageName
     * @param string $filename
     * @param string content
     *
     * @return void
     */
    public function write($storageName, $filename, $content)
    {
    }

}
