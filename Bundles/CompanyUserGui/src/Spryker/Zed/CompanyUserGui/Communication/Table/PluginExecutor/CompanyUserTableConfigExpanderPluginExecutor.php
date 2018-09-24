<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUserTableConfigExpanderPluginExecutor implements CompanyUserTableConfigExpanderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface[]
     */
    protected $companyUserTableConfigExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface[] $companyUserTableConfigExpanderPlugins
     */
    public function __construct(array $companyUserTableConfigExpanderPlugins)
    {
        $this->companyUserTableConfigExpanderPlugins = $companyUserTableConfigExpanderPlugins;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeCompanyUserTableConfigExpanderPlugins(TableConfiguration $config): TableConfiguration
    {
        foreach ($this->companyUserTableConfigExpanderPlugins as $companyUserTableConfigExpanderPlugin) {
            $config = $companyUserTableConfigExpanderPlugin->execute($config);
        }

        return $config;
    }
}
