<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Dataset\DatasetConstants;
use Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DatasetTable extends AbstractTable
{
    const REQUEST_ID_DATASET = 'id-dataset';

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface $queryContainer
     */
    public function __construct(DatasetQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setHeaders($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setRawColumns($config);
        $this->setDefaultSortField($config);
        $config->addRawColumn(DatasetConstants::COL_IS_ACTIVE);
        $config->addRawColumn(DatasetConstants::COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryContainer->queryDashboard();
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item)
    {
        $actions = implode(' ', $this->buildLinks($item));

        return [
            DatasetConstants::COL_ID_DATASET => $item[DatasetConstants::COL_ID_DATASET],
            DatasetConstants::COL_DATASET_NAME => $item[DatasetConstants::COL_DATASET_NAME],
            DatasetConstants::COL_IS_ACTIVE => $this->generateStatusLabels($item),
            DatasetConstants::COL_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config)
    {
        $config->setHeader([
            DatasetConstants::COL_ID_DATASET => '#',
            DatasetConstants::COL_DATASET_NAME => 'Dataset name',
            DatasetConstants::COL_IS_ACTIVE => 'Active',
            DatasetConstants::COL_ACTIONS => DatasetConstants::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config)
    {
        $config->setSortable([
            DatasetConstants::COL_ID_DATASET,
            DatasetConstants::COL_DATASET_NAME,
            DatasetConstants::COL_IS_ACTIVE
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config)
    {
        $config->setSearchable([
            DatasetConstants::COL_ID_DATASET,
            DatasetConstants::COL_DATASET_NAME
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config)
    {
        $config->setRawColumns([
            DatasetConstants::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(DatasetConstants::COL_ID_DATASET, DatasetConstants::SORT_DESC);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks($item)
    {
        $buttons = [];
        $buttons[] = $this->generateEditButton(
            Url::generate('/dataset/edit', [
                static::REQUEST_ID_DATASET => $item[DatasetConstants::COL_ID_DATASET],
            ]),
            'Edit'
        );
        $buttons[] = $this->generateStateChangeButton($item);
        $buttons[] = $this->generateRemoveButton(
            Url::generate('/dataset/delete', [
                static::REQUEST_ID_DATASET => $item[DatasetConstants::COL_ID_DATASET],
            ]),
            'Delete'
        );

        return $buttons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateStateChangeButton(array $item)
    {
        if ($item[DatasetConstants::COL_IS_ACTIVE]) {
            return $this->generateRemoveButton(
                Url::generate('/dataset/deactivate', [
                    self::REQUEST_ID_DATASET => $item[DatasetConstants::COL_ID_DATASET],
                ]),
                'Deactivate'
            );
        }

        return $this->generateViewButton(
            Url::generate('/dataset/activate', [
                self::REQUEST_ID_DATASET => $item[DatasetConstants::COL_ID_DATASET],
            ]),
            'Activate'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateStatusLabels(array $item)
    {
        if ($item[DatasetConstants::COL_IS_ACTIVE]) {
            return '<span class="label label-info">Active</span>';
        }

        return '<span class="label label-danger">Inactive</span>';
    }
}
