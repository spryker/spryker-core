<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Table;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationTableMap;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\NavigationGui\Communication\Controller\DeleteController;
use Spryker\Zed\NavigationGui\Communication\Controller\ToggleStatusController;
use Spryker\Zed\NavigationGui\Communication\Controller\UpdateController;
use Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface;

class NavigationTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'navigation-table';

    public const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface
     */
    protected $navigationGuiQueryContainer;

    /**
     * @param \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainerInterface $navigationGuiQueryContainer
     */
    public function __construct(NavigationGuiQueryContainerInterface $navigationGuiQueryContainer)
    {
        $this->navigationGuiQueryContainer = $navigationGuiQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $config->setHeader([
            SpyNavigationTableMap::COL_ID_NAVIGATION => '#',
            SpyNavigationTableMap::COL_NAME => 'Name',
            SpyNavigationTableMap::COL_KEY => 'Key',
            SpyNavigationTableMap::COL_IS_ACTIVE => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(SpyNavigationTableMap::COL_IS_ACTIVE);

        $config->setSortable([
            SpyNavigationTableMap::COL_ID_NAVIGATION,
            SpyNavigationTableMap::COL_NAME,
            SpyNavigationTableMap::COL_KEY,
            SpyNavigationTableMap::COL_IS_ACTIVE,
        ]);

        $config->setSearchable([
            SpyNavigationTableMap::COL_NAME,
            SpyNavigationTableMap::COL_KEY,
        ]);

        $config->setDefaultSortField(SpyNavigationTableMap::COL_ID_NAVIGATION, TableConfiguration::SORT_DESC);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->navigationGuiQueryContainer->queryNavigation();

        $queryResults = $this->runQuery($query, $config, true);

        $results = [];
        /** @var \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity */
        foreach ($queryResults as $navigationEntity) {
            $results[] = [
                SpyNavigationTableMap::COL_ID_NAVIGATION => $navigationEntity->getIdNavigation(),
                SpyNavigationTableMap::COL_NAME => $navigationEntity->getName(),
                SpyNavigationTableMap::COL_KEY => $navigationEntity->getKey(),
                SpyNavigationTableMap::COL_IS_ACTIVE => $this->getStatus($navigationEntity),
                static::COL_ACTIONS => implode(' ', $this->createActionButtons($navigationEntity)),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return array
     */
    protected function createActionButtons(SpyNavigation $navigationEntity)
    {
        $urls = [];

        $urls[] = $this->createEditButton($navigationEntity->getIdNavigation());
        $urls[] = $this->createRemoveButton($navigationEntity->getIdNavigation());
        $urls[] = $this->createStatusToggleButton($navigationEntity);

        return $urls;
    }

    /**
     * @param int $idNavigation
     *
     * @return string
     */
    protected function createEditButton($idNavigation)
    {
        return $this->generateEditButton(
            Url::generate('/navigation-gui/update', [UpdateController::PARAM_ID_NAVIGATION => $idNavigation]),
            'Edit'
        );
    }

    /**
     * @param int $idNavigation
     *
     * @return string
     */
    protected function createRemoveButton($idNavigation)
    {
        return $this->generateRemoveButton(
            Url::generate('/navigation-gui/delete', [DeleteController::PARAM_ID_NAVIGATION => $idNavigation]),
            'Delete'
        );
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return string
     */
    protected function createStatusToggleButton(SpyNavigation $navigationEntity)
    {
        if ($navigationEntity->getIsActive()) {
            return $this->generateRemoveButton(
                Url::generate('/navigation-gui/toggle-status', [ToggleStatusController::PARAM_ID_NAVIGATION => $navigationEntity->getIdNavigation()]),
                'Deactivate'
            );
        }

        return $this->generateViewButton(
            Url::generate('/navigation-gui/toggle-status', [ToggleStatusController::PARAM_ID_NAVIGATION => $navigationEntity->getIdNavigation()]),
            'Activate'
        );
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return string
     */
    protected function getStatus(SpyNavigation $navigationEntity)
    {
        if ($navigationEntity->getIsActive()) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }
}
