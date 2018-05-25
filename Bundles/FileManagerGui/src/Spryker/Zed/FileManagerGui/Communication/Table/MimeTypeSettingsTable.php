<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\Map\SpyMimeTypeTableMap;
use Spryker\Zed\FileManagerGui\Dependency\QueryContainer\FileManagerGuiToFileManagerQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MimeTypeSettingsTable extends AbstractTable
{
    const TITLE_MIME_TYPE = 'MIME Type';
    const TITLE_COMMENT = 'Comment';
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
            SpyMimeTypeTableMap::COL_NAME => static::TITLE_MIME_TYPE,
            SpyMimeTypeTableMap::COL_COMMENT => static::TITLE_COMMENT,
            SpyMimeTypeTableMap::COL_IS_ALLOWED => static::TITLE_IS_ALLOWED,
        ]);

        $config->setSortable([
            SpyMimeTypeTableMap::COL_NAME,
            SpyMimeTypeTableMap::COL_COMMENT,
        ]);

        $config->setSearchable([
            SpyMimeTypeTableMap::COL_NAME,
            SpyMimeTypeTableMap::COL_COMMENT,
        ]);

        $config->setRawColumns([
            SpyMimeTypeTableMap::COL_IS_ALLOWED,
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
        $query = $this->queryContainer->queryMimeType();
        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $mimeType) {
            $data[] = $this->mapResults($mimeType);
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
            SpyMimeTypeTableMap::COL_NAME => $item[SpyMimeTypeTableMap::COL_NAME],
            SpyMimeTypeTableMap::COL_COMMENT => $item[SpyMimeTypeTableMap::COL_COMMENT],
            SpyMimeTypeTableMap::COL_IS_ALLOWED => $this->addCheckBox($item),
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
            "<input id='mime_type_is_allowed_%s' class='mime_type_is_allowed' type='checkbox' data-id='%s' %s/>",
            $item[SpyMimeTypeTableMap::COL_ID_MIME_TYPE],
            $item[SpyMimeTypeTableMap::COL_ID_MIME_TYPE],
            $item[SpyMimeTypeTableMap::COL_IS_ALLOWED] ? "checked='checked'" : ''
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config)
    {
        $config->setDefaultSortField(SpyMimeTypeTableMap::COL_ID_MIME_TYPE, TableConfiguration::SORT_ASC);
    }
}
