<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\FileManagerGui\FileManagerGuiConstants;
use Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FileInfoTable extends AbstractTable
{
    const REQUEST_ID_FILE_INFO = 'id-file-info';

    /**
     * @var \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer
     */
    protected $queryContainer;

    /**
     * @var int
     */
    protected $idFile;

    /**
     * @var bool
     */
    private $editMode;

    /**
     * @param \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainer $queryContainer
     * @param int $idFile
     * @param bool $editMode
     */
    public function __construct(FileManagerQueryContainer $queryContainer, int $idFile, bool $editMode = false)
    {
        $this->queryContainer = $queryContainer;
        $this->idFile = $idFile;
        $this->editMode = $editMode;
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

        $config->setUrl(sprintf('file-info-table?id-file=%d', $this->idFile));

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryContainer->queryFileInfoByFkFile($this->idFile);
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
        $createdAt = date('Y-m-d H:i:s', strtotime($item[FileManagerGuiConstants::COL_FILE_INFO_CREATED_AT]));

        return [
            FileManagerGuiConstants::COL_FILE_INFO_VERSION_NAME => $item[FileManagerGuiConstants::COL_FILE_INFO_VERSION_NAME],
            FileManagerGuiConstants::COL_FILE_INFO_TYPE => $item[FileManagerGuiConstants::COL_FILE_INFO_TYPE],
            FileManagerGuiConstants::COL_FILE_INFO_CREATED_AT => $createdAt,
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
            FileManagerGuiConstants::COL_FILE_INFO_VERSION_NAME => 'Version',
            FileManagerGuiConstants::COL_FILE_INFO_TYPE => 'File type',
            FileManagerGuiConstants::COL_FILE_INFO_CREATED_AT => 'Date',
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
            FileManagerGuiConstants::COL_FILE_INFO_CREATED_AT,
            FileManagerGuiConstants::COL_FILE_INFO_VERSION_NAME,
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
            FileManagerGuiConstants::COL_FILE_INFO_CREATED_AT,
            FileManagerGuiConstants::COL_FILE_INFO_VERSION_NAME,
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
     * @param array $item
     *
     * @return array
     */
    protected function buildLinks($item)
    {
        $buttons = [];

        if ($this->editMode) {
            $buttons[] = $this->generateRemoveButton(
                Url::generate('/file-manager-gui/delete/file-info', [
                    static::REQUEST_ID_FILE_INFO => $item[FileManagerGuiConstants::COL_ID_FILE_INFO],
                ]),
                'Delete'
            );
        }

        $buttons[] = $this->generateViewButton(
            Url::generate('/file-manager-gui/download', [
                static::REQUEST_ID_FILE_INFO => $item[FileManagerGuiConstants::COL_ID_FILE_INFO],
            ]),
            'Download'
        );

        return $buttons;
    }
}
