<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DynamicEntityGuiConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const UPDATE_TIME_MINUTES = 1;

    /**
     * @var string
     */
    protected const DOWNLOAD_FILE_NAME = 'schema.yml';

    /**
     * @var string
     */
    protected const BACKEND_API_SCHEMA_PATH = APPLICATION_SOURCE_DIR . '/Generated/GlueBackend/Specification/spryker_backend_api.schema.yml';

    /**
     * @var string
     */
    protected const TABLE_PREFIX_SPY = 'spy_';

    /**
     * @var string
     */
    protected const TABLE_PREFIX_PYZ = 'pyz_';

    /**
     * Specification:
     * - Base url for a page with a list of dynamic entities.
     *
     * @api
     *
     * @see \Spryker\Zed\DynamicEntityGui\Communication\Controller\ConfigurationListController::indexAction()
     *
     * @var string
     */
    public const URL_DYNAMIC_DATA_CONFIGURATION_LIST = '/dynamic-entity-gui/configuration-list';

    /**
     * Specification:
     * - Base url for a page for creating or editing of dynamic entity.
     *
     * @api
     *
     * @see \Spryker\Zed\DynamicEntityGui\Communication\Controller\ConfigurationEditController::indexAction()
     *
     * @var string
     */
    public const URL_DYNAMIC_DATA_CONFIGURATION_EDIT = '/dynamic-entity-gui/configuration-edit';

    /**
     * Specification:
     * - Provides a list of prefixes that necessitate removal from the resource name.
     *
     * @api
     *
     * @return array<string>
     */
    public function getTablePrefixes(): array
    {
        return [
            static::TABLE_PREFIX_SPY,
            static::TABLE_PREFIX_PYZ,
        ];
    }

    /**
     * Specification:
     * - Returns the path to the backend API schema file.
     *
     * @api
     *
     * @return string
     */
    public function getBackendApiSchemaPath(): string
    {
        return static::BACKEND_API_SCHEMA_PATH;
    }

    /**
     * Specification:
     * - Returns the name to the API schema file for download.
     *
     * @api
     *
     * @return string
     */
    public function getDownloadFileName(): string
    {
        return static::DOWNLOAD_FILE_NAME;
    }

    /**
     * Specification:
     * - Returns minutes for a regenerating schema.
     *
     * @api
     *
     * @return int
     */
    public function getUpdateTime(): int
    {
        return static::UPDATE_TIME_MINUTES;
    }
}
