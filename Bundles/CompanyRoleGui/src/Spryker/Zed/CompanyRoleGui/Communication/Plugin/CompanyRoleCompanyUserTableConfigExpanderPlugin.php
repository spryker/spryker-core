<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Plugin;

use Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyRoleCompanyUserTableConfigExpanderPlugin extends AbstractPlugin implements CompanyUserTableConfigExpanderPluginInterface
{
    public const COL_COMPANY_ROLE_NAMES = 'company_role_names';

    protected const TITLE_COMPANY_ROLE_NAME = 'Roles';
    protected const TABLE_POSITION_COL_COMPANY_ROLE_NAME = 3;

    /**
     * {@inheritdoc}
     * - This plugin allows you to expand company user table with company role column.
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
                static::COL_COMPANY_ROLE_NAMES => static::TITLE_COMPANY_ROLE_NAME,
            ],
            static::TABLE_POSITION_COL_COMPANY_ROLE_NAME
        );
        $config->setHeader($configHeader);

        $configSearchable = $config->getSearchable();
        $configSearchable[] = static::COL_COMPANY_ROLE_NAMES;

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
