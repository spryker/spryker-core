<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class RestRequestValidatorConfig extends AbstractBundleConfig
{
    const VALIDATION_FILENAME_PATTERN = '*.validation.yaml';
    const VALIDATION_CACHE_FILENAME_PATTERN = '/src/Generated/Glue/Validator/validator.cache';

    /**
     * @return array
     */
    public function getValidationSchemaPathPattern()
    {
        return [
            $this->getBundlesDirectory() . '/*/*/*/src/*/Glue/*/Validation',
            APPLICATION_SOURCE_DIR . '/*/Glue/*/Validation',
        ];
    }

    /**
     * @return string
     */
    public function getValidationSchemaFileNamePattern()
    {
        return static::VALIDATION_FILENAME_PATTERN;
    }

    /**
     * @return string
     */
    public function getBundlesDirectory()
    {
        return APPLICATION_VENDOR_DIR . '/*';
    }

    /**
     * @return string
     */
    public function getValidationSchemaCacheFile()
    {
        return APPLICATION_ROOT_DIR . static::VALIDATION_CACHE_FILENAME_PATTERN;
    }
}
