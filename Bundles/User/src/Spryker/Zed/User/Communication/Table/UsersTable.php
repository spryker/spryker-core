<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Table;

use Spryker\Zed\Application\Business\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\User\Persistence\UserQueryContainer;

class UsersTable extends AbstractTable
{

    const ACTION = 'Action';
    const UPDATE_USER_URL = '/user/edit/update';
    const DEACTIVATE_USER_URL = '/user/edit/deactivate-user';
    const ACTIVATE_USER_URL = '/user/edit/activate-user';
    const DELETE_USER_URL = '/user/edit/delete';
    const PARAM_ID_USER = 'id-user';

    /**
     * @var \Spryker\Zed\User\Persistence\UserQueryContainer
     */
    protected $userQueryContainer;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainer $userQueryContainer
     */
    public function __construct(UserQueryContainer $userQueryContainer)
    {
        $this->userQueryContainer = $userQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyUserTableMap::COL_USERNAME => 'E-mail',
            SpyUserTableMap::COL_FIRST_NAME => 'First Name',
            SpyUserTableMap::COL_LAST_NAME => 'Last Name',
            SpyUserTableMap::COL_LAST_LOGIN => 'Last Login',
            SpyUserTableMap::COL_STATUS => 'Status',
            self::ACTION => self::ACTION,
        ]);

        $config->setSortable([
            SpyUserTableMap::COL_USERNAME,
            SpyUserTableMap::COL_FIRST_NAME,
            SpyUserTableMap::COL_LAST_NAME,
            SpyUserTableMap::COL_STATUS,
            SpyUserTableMap::COL_LAST_LOGIN,
        ]);

        $config->setSearchable([
            SpyUserTableMap::COL_USERNAME,
            SpyUserTableMap::COL_FIRST_NAME,
            SpyUserTableMap::COL_LAST_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $userQuery = $this->userQueryContainer->queryUser();
        $queryResults = $this->runQuery($userQuery, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = [
                SpyUserTableMap::COL_USERNAME => $item[SpyUserTableMap::COL_USERNAME],
                SpyUserTableMap::COL_FIRST_NAME => $item[SpyUserTableMap::COL_FIRST_NAME],
                SpyUserTableMap::COL_LAST_NAME => $item[SpyUserTableMap::COL_LAST_NAME],
                SpyUserTableMap::COL_LAST_LOGIN => $item[SpyUserTableMap::COL_LAST_LOGIN],
                SpyUserTableMap::COL_STATUS => $this->createStatusLabel($item),
                self::ACTION => implode(' ', $this->createActionButtons($item)),
            ];
        }

        return $results;
    }

    /**
     * @param array $user
     *
     * @return array
     */
    public function createActionButtons(array $user)
    {
        $urls = [];

        $urls[] = $this->generateEditButton(
            Url::generate(self::UPDATE_USER_URL, [
                self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
            ]),
            'Edit'
        );
        $urls[] = $this->generateViewButton(
            Url::generate(self::DEACTIVATE_USER_URL, [
                self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
            ]),
            'Deactivate'
        );
        $urls[] = $this->generateViewButton(
            Url::generate(self::ACTIVATE_USER_URL, [
                self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
            ]),
            'Activate'
        );
        $urls[] = $this->generateRemoveButton(
            Url::generate(self::DELETE_USER_URL, [
                self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
            ]),
            'Delete'
        );

        return $urls;
    }

    /**
     * @param array $user
     *
     * @return string
     */
    public function createStatusLabel(array $user)
    {
        $statusLabel = '';
        switch ($user[SpyUserTableMap::COL_STATUS]) {
            case SpyUserTableMap::COL_STATUS_ACTIVE:
                $statusLabel = '<span class="label label-success" title="Active">Active</span>';
                break;
            case SpyUserTableMap::COL_STATUS_BLOCKED:
                $statusLabel = '<span class="label label-danger" title="Deactivated">Deactivated</span>';
                break;
            case SpyUserTableMap::COL_STATUS_DELETED:
                $statusLabel = '<span class="label label-default" title="Deleted">Deleted</span>';
                break;
        }

        return $statusLabel;
    }

}
