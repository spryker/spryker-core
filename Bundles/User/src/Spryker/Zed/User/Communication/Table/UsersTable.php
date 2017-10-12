<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Table;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface;
use Spryker\Zed\User\Persistence\UserQueryContainerInterface;

class UsersTable extends AbstractTable
{
    const ACTION = 'Action';
    const UPDATE_USER_URL = '/user/edit/update';
    const DEACTIVATE_USER_URL = '/user/edit/deactivate-user';
    const ACTIVATE_USER_URL = '/user/edit/activate-user';
    const DELETE_USER_URL = '/user/edit/delete';
    const PARAM_ID_USER = 'id-user';

    /**
     * @var \Spryker\Zed\User\Persistence\UserQueryContainerInterface
     */
    protected $userQueryContainer;

    /**
     * @var \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected $utilDateTimeService;

    /**
     * @var \Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface[]
     */
    protected $usersTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\User\Persistence\UserQueryContainerInterface $userQueryContainer
     * @param \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface $utilDateTimeService
     * @param \Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface[] $userTableExpanderPlugins
     */
    public function __construct(UserQueryContainerInterface $userQueryContainer, UtilDateTimeServiceInterface $utilDateTimeService, array $userTableExpanderPlugins)
    {
        $this->userQueryContainer = $userQueryContainer;
        $this->utilDateTimeService = $utilDateTimeService;
        $this->usersTableExpanderPlugins = $userTableExpanderPlugins;
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

        $config->setRawColumns([SpyUserTableMap::COL_STATUS, self::ACTION]);

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
     * @return array
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
                SpyUserTableMap::COL_LAST_LOGIN => $this->getLastLoginDateTime($item),
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
        $urls = $this->generateUsersTableExpanderPluginsActionButtons($user);

        $urls[] = $this->generateEditButton(
            Url::generate(self::UPDATE_USER_URL, [
                self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
            ]),
            'Edit'
        );

        $urls[] = $this->createStatusButton($user);

        $urls[] = $this->generateRemoveButton(self::DELETE_USER_URL, 'Delete', [
            self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
        ]);

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

    /**
     * @param array $user
     *
     * @return array
     */
    protected function createStatusButton(array $user)
    {
        if ($user[SpyUserTableMap::COL_STATUS] === SpyUserTableMap::COL_STATUS_BLOCKED) {
            return $this->generateViewButton(
                Url::generate(self::ACTIVATE_USER_URL, [
                    self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
                ]),
                'Activate'
            );
        }

        return $urls[] = $this->generateViewButton(
            Url::generate(self::DEACTIVATE_USER_URL, [
                self::PARAM_ID_USER => $user[SpyUserTableMap::COL_ID_USER],
            ]),
            'Deactivate'
        );
    }

    /**
     * @param array $user
     *
     * @return string[]
     */
    protected function generateUsersTableExpanderPluginsActionButtons(array $user)
    {
        $actionButtons = [];
        foreach ($this->usersTableExpanderPlugins as $usersTableExpanderPlugin) {
            $actionButtons = array_merge(
                $actionButtons,
                $this->generateUsersTableExpanderPluginActionButtons($usersTableExpanderPlugin, $user)
            );
        }

        return $actionButtons;
    }

    /**
     * @param \Spryker\Zed\User\Dependency\Plugin\UsersTableExpanderPluginInterface $usersTableExpanderPlugin
     * @param array $user
     *
     * @return string[]
     */
    protected function generateUsersTableExpanderPluginActionButtons(UsersTableExpanderPluginInterface $usersTableExpanderPlugin, array $user)
    {
        $pluginActionButtons = [];
        foreach ($usersTableExpanderPlugin->getActionButtonDefinitions($user) as $buttonTransfer) {
            $pluginActionButtons[] = $this->generateButton(
                $buttonTransfer->getUrl(),
                $buttonTransfer->getTitle(),
                $buttonTransfer->getDefaultOptions(),
                $buttonTransfer->getCustomOptions()
            );
        }

        return $pluginActionButtons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getLastLoginDateTime(array $item)
    {
        if (empty($item[SpyUserTableMap::COL_LAST_LOGIN])) {
            return 'N/A';
        }

        return $this->utilDateTimeService->formatDateTime($item[SpyUserTableMap::COL_LAST_LOGIN]);
    }
}
