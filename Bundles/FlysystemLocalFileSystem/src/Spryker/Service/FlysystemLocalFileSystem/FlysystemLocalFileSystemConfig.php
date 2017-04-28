<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FlysystemLocalFileSystem;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\FlysystemLocalFileSystem\FlysystemLocalFileSystemConstants;

class FlysystemLocalFileSystemConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getFilesystemConfig()
    {
        return $this->get(FlysystemLocalFileSystemConstants::FILESYSTEM_STORAGE)[FlysystemLocalFileSystemConstants::FILESYSTEM_SERVICE];
    }

}
