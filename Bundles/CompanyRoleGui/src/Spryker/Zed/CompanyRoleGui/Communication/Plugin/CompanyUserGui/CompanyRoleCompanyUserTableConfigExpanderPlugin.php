<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig;
use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableConfigExpanderPluginInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 */
class CompanyRoleCompanyUserTableConfigExpanderPlugin extends AbstractPlugin implements CompanyUserTableConfigExpanderPluginInterface
{
    protected const TITLE_COMPANY_ROLE_NAMES = 'Roles';

    /**
     * {@inheritDoc}
     * - Expands company user table with company role column.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $configHeader = $config->getHeader() + [
            CompanyRoleGuiConfig::COL_COMPANY_ROLE_NAMES => static::TITLE_COMPANY_ROLE_NAMES,
        ];
        $config->setHeader($configHeader);

        $config->addRawColumn(CompanyRoleGuiConfig::COL_COMPANY_ROLE_NAMES);

        return $config;
    }
}
