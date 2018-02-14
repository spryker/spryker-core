<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\FileManagerGui\FileManagerGuiConstants;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FileTable extends AbstractTable
{
    const REQUEST_ID_FILE = 'id-file';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerBridgeInterface $queryContainer
     */
    public function __construct(FileManagerGuiToFileManagerQueryContainerBridgeInterface $queryContainer)
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
            FileManagerGuiConstants::COL_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            FileManagerGuiConstants::COL_FILE_NAME => $item[FileManagerGuiConstants::COL_FILE_NAME],
            FileManagerGuiConstants::COL_ACTIONS => $actions,
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
            FileManagerGuiConstants::COL_ID_FILE => '#',
            FileManagerGuiConstants::COL_FILE_NAME => 'File name',
            FileManagerGuiConstants::COL_ACTIONS => FileManagerGuiConstants::COL_ACTIONS,
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
            FileManagerGuiConstants::COL_ID_FILE,
            FileManagerGuiConstants::COL_FILE_NAME,
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
            FileManagerGuiConstants::COL_ID_FILE,
            FileManagerGuiConstants::COL_FILE_NAME,
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
            FileManagerGuiConstants::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(FileManagerGuiConstants::COL_ID_FILE, FileManagerGuiConstants::SORT_DESC);
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks($item)
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate('/file-manager-gui/view', [
                static::REQUEST_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            ]),
            'View'
        );
        $buttons[] = $this->generateEditButton(
            Url::generate('/file-manager-gui/edit', [
                static::REQUEST_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            ]),
            'Edit'
        );
        $buttons[] = $this->generateRemoveButton(
            Url::generate('/file-manager-gui/delete/file', [
                static::REQUEST_ID_FILE => $item[FileManagerGuiConstants::COL_ID_FILE],
            ]),
            'Delete'
        );

        return $buttons;
    }
}
