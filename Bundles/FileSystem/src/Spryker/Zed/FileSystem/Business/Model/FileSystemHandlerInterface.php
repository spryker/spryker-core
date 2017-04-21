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
     * @return string|false The file contents or false on failure.
     */
    public function read($fileSystemName, $path);

}
