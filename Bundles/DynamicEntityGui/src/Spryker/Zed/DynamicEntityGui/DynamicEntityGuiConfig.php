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
}
