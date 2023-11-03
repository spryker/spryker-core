<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class DocumentationGeneratorApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const API_SCHEMA_STORAGE_KEY_PATTERN = 'documentation:api:%s.schema.yml';

    /**
     * Specification:
     * - Returns file path with generated documentation.
     *
     * @api
     *
     * @param string $applicationName
     *
     * @return string
     */
    public function getGeneratedFullFileName(string $applicationName): string
    {
        return sprintf(
            '%s/src/Generated/Glue%s/Specification/spryker_%s_api.schema.yml',
            APPLICATION_ROOT_DIR,
            ucfirst($applicationName),
            strtolower($applicationName),
        );
    }

    /**
     * Specification:
     * - Returns a Storage key pattern for the API schema.
     *
     * @api
     *
     * @return string
     */
    public function getApiSchemaStorageKeyPattern(): string
    {
        return static::API_SCHEMA_STORAGE_KEY_PATTERN;
    }
}
