<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface CompanyUserTableExpanderPluginExecutorInterface
{
    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeConfigExpanderPlugins(TableConfiguration $config): TableConfiguration;

    /**
     * @param array $companyUserDataItem
     *
     * @return array
     */
    public function executePrepareDataExpanderPlugins(array $companyUserDataItem): array;
}
