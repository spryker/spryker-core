<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Business\FileSystem;

use Spryker\Shared\FileSystem\FileSystemConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FileSystemConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getStorageConfig()
    {
        return $this->get(FileSystemConstants::FILESYSTEM_STORAGE);
    }

}
