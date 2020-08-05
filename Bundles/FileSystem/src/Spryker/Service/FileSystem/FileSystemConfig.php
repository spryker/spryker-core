<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\FileSystem\FileSystemConstants;

class FileSystemConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return array
     */
    public function getFilesystemConfig()
    {
        return $this->get(FileSystemConstants::FILESYSTEM_SERVICE, []);
    }
}
