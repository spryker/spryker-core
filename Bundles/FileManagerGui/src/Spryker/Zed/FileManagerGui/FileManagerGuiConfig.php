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
    /**
     * @var bool
     */
    protected const IS_FILE_EXTENSION_VALIDATION_ENABLED = false;

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultFileMaxSize()
    {
        return $this->get(FileManagerGuiConstants::DEFAULT_FILE_MAX_SIZE);
    }

    /**
     * Specification:
     * - Defines whether the extension validation for the uploaded file is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isFileExtensionValidationEnabled(): bool
    {
        return static::IS_FILE_EXTENSION_VALIDATION_ENABLED;
    }
}
