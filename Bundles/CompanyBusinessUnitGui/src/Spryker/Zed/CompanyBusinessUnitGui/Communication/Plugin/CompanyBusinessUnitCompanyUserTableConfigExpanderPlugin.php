<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Plugin;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitGui\Communication\CompanyBusinessUnitGuiCommunicationFactory getFactory()
 */
class CompanyBusinessUnitCompanyUserTableConfigExpanderPlugin extends AbstractPlugin implements CompanyUserTableConfigExpanderPluginInterface
{
    public const COL_COMPANY_BUSINESS_UNIT_NAME = 'company_business_unit_name';

    protected const TITLE_COMPANY_BUSINESS_UNIT_NAME = 'Company Business Unit';
    protected const TABLE_POSITION_COMPANY_BUSINESS_UNIT_NAME = 2;

    /**
     * {@inheritdoc}
     * - This plugin allows you to expand config options of company user table with company business unit column.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $configHeader = $config->getHeader();
        $configHeader = $this->addArrayItemToArrayPosition(
            $configHeader,
            [
                static::COL_COMPANY_BUSINESS_UNIT_NAME => static::TITLE_COMPANY_BUSINESS_UNIT_NAME,
            ],
            static::TABLE_POSITION_COMPANY_BUSINESS_UNIT_NAME
        );
        $config->setHeader($configHeader);

        $configSearchable = $config->getSearchable();
        $configSearchable[] = static::COL_COMPANY_BUSINESS_UNIT_NAME;

        return $config;
    }

    /**
     * @param array $data
     * @param array $item
     * @param int $position
     *
     * @return array
     */
    protected function addArrayItemToArrayPosition(array $data, array $item, int $position): array
    {
        return array_merge(
            array_slice($data, 0, $position, true),
            $item,
            array_slice($data, $position, null, true)
        );
    }
}
