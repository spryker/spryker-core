<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Business\FileSystem;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Business\FileSystem\FileSystemServiceFactory getFactory()
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
     * @param string $storageName
     * @param string $filename
     *
     * @return void
     */
    public function read($storageName, $filename)
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
