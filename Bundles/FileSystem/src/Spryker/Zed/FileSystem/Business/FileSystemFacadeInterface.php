<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Business;

/**
 * @method \Spryker\Zed\FileSystem\FileSystemConfig getConfig()
 * @method \Spryker\Zed\FileSystem\Business\FileSystemBusinessFactory getFactory()
 */
interface FileSystemFacadeInterface
{

    /**
     * @api
     *
     * @param string $fileSystemName
     * @param string $path
     *
     * @return string|null|false
     */
    public function read($fileSystemName, $path);

}
