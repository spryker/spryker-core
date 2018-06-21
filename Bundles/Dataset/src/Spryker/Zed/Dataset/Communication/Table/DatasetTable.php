<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Table;

use Orm\Zed\Dataset\Persistence\Map\SpyDatasetTableMap;
use Orm\Zed\Dataset\Persistence\SpyDatasetQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DatasetTable extends AbstractTable
{
    public const REQUEST_ID_DATASET = 'id-dataset';
    public const COL_ID_DATASET = SpyDatasetTableMap::COL_ID_DATASET;
    public const COL_DATASET_NAME = SpyDatasetTableMap::COL_NAME;
    public const COL_ACTIONS = 'Actions';
    public const SORT_DESC = TableConfiguration::SORT_DESC;
    public const COL_IS_ACTIVE = SpyDatasetTableMap::COL_IS_ACTIVE;

    public const DATASET_ACTIVATE_URL = '/dataset/activate';
    public const DATASET_DEACTIVATE_URL = '/dataset/deactivate';
    public const DATASET_EDIT_URL = '/dataset/edit';
    public const DATASET_DOWNLOAD_URL = '/dataset/download';
    public const DATASET_DELETE_URL = '/dataset/delete';

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Orm\Zed\Dataset\Persistence\SpyDatasetQuery
     */
    protected $datasetQuery;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface $repository
     * @param \Orm\Zed\Dataset\Persistence\SpyDatasetQuery $datasetQuery
     */
    public function __construct(DatasetRepositoryInterface $repository, SpyDatasetQuery $datasetQuery)
    {
        $this->repository = $repository;
        $this->datasetQuery = $datasetQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setHeaders($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setRawColumns($config);
        $this->setDefaultSortField($config);
        $config->addRawColumn(static::COL_IS_ACTIVE);
        $config->addRawColumn(static::COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->datasetQuery, $config);
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
    protected function mapResults(array $item): array
    {
        $actions = implode(' ', $this->buildLinks($item));

        return [
            static::COL_ID_DATASET => $item[static::COL_ID_DATASET],
            static::COL_DATASET_NAME => $item[static::COL_DATASET_NAME],
            static::COL_IS_ACTIVE => $this->generateStatusLabels($item),
            static::COL_ACTIONS => $actions,
        ];
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config): void
    {
        $config->setHeader([
            static::COL_ID_DATASET => '#',
            static::COL_DATASET_NAME => 'Dataset name',
            static::COL_IS_ACTIVE => 'Active',
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config): void
    {
        $config->setSortable([
            static::COL_ID_DATASET,
            static::COL_DATASET_NAME,
            static::COL_IS_ACTIVE,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config): void
    {
        $config->setSearchable([
            static::COL_ID_DATASET,
            static::COL_DATASET_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config): void
    {
        $config->setRawColumns([
            static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config): void
    {
        $config->setDefaultSortField(static::COL_ID_DATASET, static::SORT_DESC);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks(array $item): array
    {
        $buttons = [];
        $buttons[] = $this->generateViewButton(
            Url::generate(static::DATASET_DOWNLOAD_URL, [
                static::REQUEST_ID_DATASET => $item[static::COL_ID_DATASET],
            ]),
            'Download'
        );
        $buttons[] = $this->generateEditButton(
            Url::generate(static::DATASET_EDIT_URL, [
                static::REQUEST_ID_DATASET => $item[static::COL_ID_DATASET],
            ]),
            'Edit'
        );
        $buttons[] = $this->generateStateChangeButton($item);
        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::DATASET_DELETE_URL, [
                static::REQUEST_ID_DATASET => $item[static::COL_ID_DATASET],
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
    protected function generateStateChangeButton(array $item): string
    {
        if ($item[static::COL_IS_ACTIVE]) {
            return $this->generateRemoveButton(
                Url::generate(static::DATASET_DEACTIVATE_URL, [
                    static::REQUEST_ID_DATASET => $item[static::COL_ID_DATASET],
                ]),
                'Deactivate'
            );
        }

        return $this->generateViewButton(
            Url::generate(static::DATASET_ACTIVATE_URL, [
                static::REQUEST_ID_DATASET => $item[static::COL_ID_DATASET],
            ]),
            'Activate'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateStatusLabels(array $item): string
    {
        if ($item[static::COL_IS_ACTIVE]) {
            return '<span class="label label-info">Active</span>';
        }

        return '<span class="label label-danger">Inactive</span>';
    }
}
