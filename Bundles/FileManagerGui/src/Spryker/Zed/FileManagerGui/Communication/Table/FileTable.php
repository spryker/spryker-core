<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\FileManagerGui\FileManagerGuiConstants;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FileTable extends AbstractTable
{
    const COL_ID_FILE = SpyFileTableMap::COL_ID_FILE;
    const COL_FILE_NAME = SpyFileTableMap::COL_FILE_NAME;
    const COL_ACTIONS = 'Actions';

    const REQUEST_ID_FILE = 'id-file';
    const VIEW_TITLE = 'View';
    const EDIT_TITLE = 'Edit';
    const DELETE_TITLE = 'Delete';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var int
     */
    protected $fileDirectoryId;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface $queryContainer
     * @param int|null $fileDirectoryId
     */
    public function __construct(
        FileManagerGuiToFileManagerQueryContainerInterface $queryContainer,
        $fileDirectoryId = null
    ) {
        $this->queryContainer = $queryContainer;

        if ($fileDirectoryId) {
            $this->fileDirectoryId = $fileDirectoryId;
        }
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
        $query = $this->queryContainer->queryFiles()->orderByIdFile(Criteria::DESC);

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
            Url::generate(FileManagerGuiConstants::FILE_MANAGER_GUI_VIEW_URL, [
                static::REQUEST_ID_FILE => $item[static::COL_ID_FILE],
            ]),
            static::VIEW_TITLE
        );
        $buttons[] = $this->generateEditButton(
            Url::generate(FileManagerGuiConstants::FILE_MANAGER_GUI_EDIT_URL, [
                static::REQUEST_ID_FILE => $item[static::COL_ID_FILE],
            ]),
            static::EDIT_TITLE
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate(FileManagerGuiConstants::FILE_MANAGER_GUI_DELETE_URL, [
                static::REQUEST_ID_FILE => $item[static::COL_ID_FILE],
            ]),
            static::DELETE_TITLE
        );

        return $buttons;
    }
}
