<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class DynamicEntityBackendApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const GLOSSARY_KEY_ERROR_INVALID_DATA_FORMAT = 'dynamic_entity.validation.invalid_data_format';

    /**
     * @var string
     */
    public const GLOSSARY_KEY_ERROR_ENTITY_DOES_NOT_EXIST = 'dynamic_entity.validation.entity_does_not_exist';

    /**
     * @var string
     */
    protected const ROUTE_PREFIX = 'dynamic-entity';

    /**
     * @var string
     */
    protected const LOG_FILE_PATH = '%s/data/dynamic-entity/logs/%s.log';

    /**
     * @see \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig::API_SCHEMA_STORAGE_KEY_PATTERN
     *
     * @var string
     */
    protected const BACKEND_API_SCHEMA_STORAGE_KEY = 'documentation:api:backend.schema.yml';

    /**
     * Specification:
     * - Returns a route prefix value for a dynamic entity.
     *
     * @api
     *
     * @return string
     */
    public function getRoutePrefix(): string
    {
        return static::ROUTE_PREFIX;
    }

    /**
     * Specification:
     * - Returns absolute file path for dynamic entities logs.
     *
     * @api
     *
     * @return string
     */
    public function getLogFilepath(): string
    {
        return sprintf(static::LOG_FILE_PATH, APPLICATION_ROOT_DIR, date('Y-m-d'));
    }

    /**
     * Specification:
     * - Defines whether logging for dynamic entities is enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return true;
    }

    /**
     * Specification:
     * - Defines default pagination page size.
     *
     * @api
     *
     * @return int
     */
    public function getDefaultPaginationLimit(): int
    {
        return 1000;
    }

    /**
     * Specification:
     * - Returns a Storage key for the Backend API schema.
     *
     * @api
     *
     * @return string
     */
    public function getBackendApiSchemaStorageKey(): string
    {
        return static::BACKEND_API_SCHEMA_STORAGE_KEY;
    }
}
