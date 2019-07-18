<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Table;

use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyRole\Persistence\Map\SpyCompanyRoleTableMap;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyRoleTable extends AbstractTable
{
    protected const COL_ACTIONS = 'Actions';

    protected const HEADER_ID_COMPANY_ROLE = 'Role ID';
    protected const HEADER_NAME_COMPANY_ROLE = 'Name';
    protected const HEADER_NAME_COMPANY = 'Company Name';
    protected const HEADER_ACTIONS = 'Actions';

    protected const PARAM_ID_COMPANY_ROLE = 'id-company-role';

    protected const UPDATE_ROLE_URL = '/company-role-gui/edit-company-role';
    protected const DELETE_ROLE_URL = '/company-role-gui/delete-company-role/confirm-delete';

    protected const BUTTON_TITLE_DELETE = 'Delete';
    protected const BUTTON_TITLE_EDIT = 'Edit';

    /**
     * @var \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    protected $companyRoleQuery;

    /**
     * @param \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery $companyRoleQuery
     */
    public function __construct(SpyCompanyRoleQuery $companyRoleQuery)
    {
        $this->companyRoleQuery = $companyRoleQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE => static::HEADER_ID_COMPANY_ROLE,
            SpyCompanyTableMap::COL_NAME => static::HEADER_NAME_COMPANY,
            SpyCompanyRoleTableMap::COL_NAME => static::HEADER_NAME_COMPANY_ROLE,
            static::COL_ACTIONS => static::HEADER_ACTIONS,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);

        $config->setSortable([
            SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE,
            SpyCompanyRoleTableMap::COL_NAME,
            SpyCompanyTableMap::COL_NAME,
        ]);

        $config->setSearchable([
            SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE,
            SpyCompanyRoleTableMap::COL_NAME,
            SpyCompanyTableMap::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $queryResults = $this->runQuery($this->prepareQuery(), $config);
        $results = [];

        foreach ($queryResults as $companyRole) {
            $results[] = [
                SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE => $companyRole[SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE],
                SpyCompanyTableMap::COL_NAME => $companyRole[SpyCompanyTableMap::COL_NAME],
                SpyCompanyRoleTableMap::COL_NAME => $companyRole[SpyCompanyRoleTableMap::COL_NAME],
                static::COL_ACTIONS => implode(' ', $this->createTableActions($companyRole)),
            ];
        }

        return $results;
    }

    /**
     * @param array $companyRole
     *
     * @return array
     */
    protected function createTableActions(array $companyRole): array
    {
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            Url::generate(self::UPDATE_ROLE_URL, [self::PARAM_ID_COMPANY_ROLE => $companyRole[SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE]]),
            static::BUTTON_TITLE_EDIT
        );

        $buttons[] = $this->generateRemoveButton(
            Url::generate(self::DELETE_ROLE_URL, [self::PARAM_ID_COMPANY_ROLE => $companyRole[SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE]]),
            static::BUTTON_TITLE_DELETE
        );

        return $buttons;
    }

    /**
     * @module Company
     *
     * @return \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery
     */
    protected function prepareQuery(): SpyCompanyRoleQuery
    {
        /** @var \Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleQuery $query */
        $query = $this->companyRoleQuery
            ->leftJoinWithCompany()
            ->select([
                SpyCompanyRoleTableMap::COL_ID_COMPANY_ROLE,
                SpyCompanyTableMap::COL_NAME,
                SpyCompanyRoleTableMap::COL_NAME,
            ]);

        return $query;
    }
}
