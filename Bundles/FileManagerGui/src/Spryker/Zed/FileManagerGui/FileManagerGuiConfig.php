<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui;

use Spryker\Shared\FileManagerGui\FileManagerGuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FileManagerGuiConfig extends AbstractBundleConfig
{
    const DEFAULT_PROCESS_LOCATION = APPLICATION_ROOT_DIR . '/config/Zed/oms';

    /**
     * @return string
     */
    public function getMaxSize(): string
    {
        return $this->get(FileManagerGuiConstants::MAX_FILE_SIZE, FileManagerGuiConstants::DEFAULT_MAX_FILE_SIZE);
    }
}
