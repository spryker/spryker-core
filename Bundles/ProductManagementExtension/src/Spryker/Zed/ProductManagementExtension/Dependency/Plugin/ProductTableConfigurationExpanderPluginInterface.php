<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

/**
 * Provides capabilities to expand product table configuration.
 */
interface ProductTableConfigurationExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands abstract product table configuration.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandTableConfiguration(TableConfiguration $config): TableConfiguration;
}
