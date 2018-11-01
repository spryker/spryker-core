<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CompanyUnitAddressTablePluginExecutorInterface
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeTableConfigExpanderPlugins(TableConfiguration $config): TableConfiguration;

    /**
     * @return array
     */
    public function executeTableHeaderExpanderPlugins(): array;

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeTableDataExpanderPlugins(array $item): array;
}
