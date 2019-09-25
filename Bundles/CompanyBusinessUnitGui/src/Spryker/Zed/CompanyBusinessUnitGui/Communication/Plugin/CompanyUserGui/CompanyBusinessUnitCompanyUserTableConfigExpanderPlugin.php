<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin\CompanyUserGui;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableConfigExpanderPluginInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnitGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiConfig getConfig()
 */
class CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin extends AbstractPlugin implements CompanyUserTableConfigExpanderPluginInterface
{
    public const COL_COMPANY_BUSINESS_UNIT_NAME = 'company_business_unit_name';

    protected const TITLE_COMPANY_BUSINESS_UNIT_NAME = 'Company Business Unit';

    /**
     * {@inheritDoc}
     * - Expands config options of company user table with company business unit column.
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
            static::COL_COMPANY_BUSINESS_UNIT_NAME => static::TITLE_COMPANY_BUSINESS_UNIT_NAME,
        ];
        $config->setHeader($configHeader);

        return $config;
    }
}
