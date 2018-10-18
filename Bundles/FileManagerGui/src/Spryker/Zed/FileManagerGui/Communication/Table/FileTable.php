<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FileTable extends AbstractTable
{
    protected const COL_ID_FILE = SpyFileTableMap::COL_ID_FILE;
    protected const COL_FILE_NAME = SpyFileTableMap::COL_FILE_NAME;
    protected const COL_ACTIONS = 'Actions';

    protected const URL_FILE_MANAGER_GUI_VIEW = '/file-manager-gui/view-file';
    protected const URL_FILE_MANAGER_GUI_EDIT = '/file-manager-gui/edit-file';
    protected const URL_FILE_MANAGER_GUI_DELETE = '/file-manager-gui/delete-file/file';

    protected const VIEW_TITLE = 'View';
    protected const EDIT_TITLE = 'Edit';
    protected const DELETE_TITLE = 'Delete';

    protected const REQUEST_ID_FILE = 'id-file';

    /**
     * @var \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    protected $fileQuery;

    /**
     * @var int
     */
    protected $fileDirectoryId;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileQuery $fileQuery
     * @param int|null $fileDirectoryId
     */
    public function __construct(
        SpyFileQuery $fileQuery,
        $fileDirectoryId = null
    ) {
        $this->fileQuery = $fileQuery;
        $this->fileDirectoryId = $fileDirectoryId;
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

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->fileQuery->orderByIdFile(Criteria::DESC);

        if ($this->fileDirectoryId) {
            $query->filterByFkFileDirectory($this->fileDirectoryId);
        }

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
            static::COL_ID_FILE => $item[static::COL_ID_FILE],
            static::COL_FILE_NAME => $item[static::COL_FILE_NAME],
            static::COL_ACTIONS => $actions,
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
            static::COL_ID_FILE => '#',
            static::COL_FILE_NAME => 'File name',
            static::COL_ACTIONS => static::COL_ACTIONS,
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
            static::COL_ID_FILE,
            static::COL_FILE_NAME,
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
            static::COL_ID_FILE,
            static::COL_FILE_NAME,
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
            static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(static::COL_ID_FILE, TableConfiguration::SORT_DESC);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_FILE_MANAGER_GUI_VIEW, [
                static::REQUEST_ID_FILE => $item[static::COL_ID_FILE],
            ]),
            static::VIEW_TITLE
        );
        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_FILE_MANAGER_GUI_EDIT, [
                static::REQUEST_ID_FILE => $item[static::COL_ID_FILE],
            ]),
            static::EDIT_TITLE
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(static::URL_FILE_MANAGER_GUI_DELETE, [
                static::REQUEST_ID_FILE => $item[static::COL_ID_FILE],
            ]),
            static::DELETE_TITLE
        );

        return $buttons;
    }
}
