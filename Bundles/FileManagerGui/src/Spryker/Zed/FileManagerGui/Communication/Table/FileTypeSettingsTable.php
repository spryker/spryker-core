<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\Map\SpyFileTypeTableMap;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class FileTypeSettingsTable extends AbstractTable
{
    const TITLE_FILE_TYPE = 'File Type';
    const TITLE_IS_ALLOWED = 'Is Allowed';

    /**
     * @var \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface $queryContainer
     */
    public function __construct(FileManagerGuiToFileManagerQueryContainerInterface $queryContainer)
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
        $config->setHeader([
            SpyFileTypeTableMap::COL_EXTENSION => self::TITLE_FILE_TYPE,
            SpyFileTypeTableMap::COL_IS_ALLOWED => self::TITLE_IS_ALLOWED,
        ]);

        $config->setSortable([
            SpyFileTypeTableMap::COL_EXTENSION,
        ]);

        $config->setSearchable([
            SpyFileTypeTableMap::COL_EXTENSION,
        ]);

        $config->setRawColumns([
            SpyFileTypeTableMap::COL_IS_ALLOWED,
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
        $data = [];
        $query = $this->queryContainer->queryFileType();
        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $fileType) {
            $data[] = $this->mapResults($fileType);
        }

        return $data;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item)
    {
        return [
            SpyFileTypeTableMap::COL_EXTENSION => $item[SpyFileTypeTableMap::COL_EXTENSION],
            SpyFileTypeTableMap::COL_IS_ALLOWED => $this->addCheckBox($item),
        ];
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function addCheckbox(array $item)
    {
        return sprintf(
            "<input id='file_type_is_allowed_%s' class='file_type_is_allowed' type='checkbox' data-id='%s' %s/>",
            $item[SpyFileTypeTableMap::COL_ID_FILE_TYPE],
            $item[SpyFileTypeTableMap::COL_ID_FILE_TYPE],
            $item[SpyFileTypeTableMap::COL_IS_ALLOWED] ? "checked='checked'" : ''
        );
    }

//
//    /**
//     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
//     *
//     * @return void
//     */
//    protected function setDefaultSortField(TableConfiguration $config)
//    {
//        $config->setDefaultSortField(FileManagerGuiConstants::COL_FILE_TYPE_EXTENSION, TableConfiguration::SORT_ASC);
//    }
}
