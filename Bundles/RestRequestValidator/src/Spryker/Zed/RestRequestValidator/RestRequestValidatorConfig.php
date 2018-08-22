<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class RestRequestValidatorConfig extends AbstractBundleConfig
{
    protected const VALIDATION_FILENAME_PATTERN = '*.validation.yaml';
    protected const VALIDATION_CACHE_FILENAME_PATTERN = '/src/Generated/Glue/Validator/validation.cache';
    protected const PATH_MASK_PROJECT_VALIDATION = '/*/*/*/src/*/Glue/*/Validation';
    protected const PATH_MASK_CORE_VALIDATION = '/*/*/Glue/*/Validation';

    /**
     * @return string[]
     */
    public function getValidationSchemaPathPattern(): array
    {
        return [
            APPLICATION_VENDOR_DIR . static::PATH_MASK_CORE_VALIDATION,
            APPLICATION_SOURCE_DIR . static::PATH_MASK_PROJECT_VALIDATION,
        ];
    }

    /**
     * @return string
     */
    public function getValidationSchemaFileNamePattern(): string
    {
        return static::VALIDATION_FILENAME_PATTERN;
    }

    /**
     * @return string
     */
    public function getValidationSchemaCacheFile(): string
    {
        return APPLICATION_ROOT_DIR . static::VALIDATION_CACHE_FILENAME_PATTERN;
    }
}
