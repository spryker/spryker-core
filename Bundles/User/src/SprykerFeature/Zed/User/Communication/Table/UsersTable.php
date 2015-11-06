<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Table;

use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use SprykerFeature\Zed\User\Persistence\UserQueryContainer;

class UsersTable extends AbstractTable
{

    const ACTION = 'Action';
    const UPDATE_USER_URL = '/user/edit/update?id-user=%d';
    const DEACTIVATE_USER_URL = '/user/edit/deactivate-user?id-user=%d';
    const ACTIVATE_USER_URL = '/user/edit/activate-user?id-user=%d';
    const DELETE_USER_URL = '/user/edit/delete?id-user=%d';

    /**
     * @var UserQueryContainer
     */
    private $userQueryContainer;

    /**
     * @param UserQueryContainer $userQueryContainer
     */
    public function __construct(UserQueryContainer $userQueryContainer)
    {
        $this->userQueryContainer = $userQueryContainer;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyUserTableMap::COL_USERNAME => 'User ID',
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
     * @param TableConfiguration $config
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
                self::ACTION => $this->createActionButtons($item),
            ];
        }

        return $results;
    }

    /**
     * @param array $user
     *
     * @return string
     */
    public function createActionButtons(array $user)
    {
        $actionButtons = sprintf(
            '<a class="btn btn-xs btn-white" href="' . self::UPDATE_USER_URL . '">
                 Edit
            </a>
            <a class="btn btn-xs btn-white" href="' . self::DEACTIVATE_USER_URL . '">
                 Deactivate
            </a>
            <a class="btn btn-xs btn-white" href="' . self::ACTIVATE_USER_URL . '">
                 Activate
            </a>
            <a class="btn btn-xs btn-white" href="' . self::DELETE_USER_URL . '">
                 Delete
            </a>',

            $user[SpyUserTableMap::COL_ID_USER],
            $user[SpyUserTableMap::COL_ID_USER],
            $user[SpyUserTableMap::COL_ID_USER],
            $user[SpyUserTableMap::COL_ID_USER]
        );

        return $actionButtons;
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
