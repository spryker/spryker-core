<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Communication\Table;

use Orm\Zed\FileManager\Persistence\Map\SpyFileInfoTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

abstract class FileInfoTable extends AbstractTable
{
    const COL_ID_FILE_INFO = SpyFileInfoTableMap::COL_ID_FILE_INFO;
    const COL_FILE_INFO_VERSION_NAME = SpyFileInfoTableMap::COL_VERSION_NAME;
    const COL_FILE_INFO_TYPE = SpyFileInfoTableMap::COL_TYPE;
    const COL_FILE_INFO_CREATED_AT = SpyFileInfoTableMap::COL_CREATED_AT;
    const COL_ACTIONS = 'Actions';

    const REQUEST_ID_FILE_INFO = 'id-file-info';

    /**
     * @var \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    protected $fileInfoQuery;

    /**
     * @var int
     */
    protected $idFile;

    /**
     * @param \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery $fileInfoQuery
     * @param int $idFile
     */
    public function __construct(SpyFileInfoQuery $fileInfoQuery, int $idFile)
    {
        $this->fileInfoQuery = $fileInfoQuery;
        $this->idFile = $idFile;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    abstract protected function buildLinks($item);

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
        $query = $this->fileInfoQuery
            ->filterByFkFile($this->idFile)
            ->orderByVersion(Criteria::DESC);

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
        $createdAt = date('Y-m-d H:i:s', strtotime($item[static::COL_FILE_INFO_CREATED_AT]));

        return [
            static::COL_FILE_INFO_VERSION_NAME => $item[static::COL_FILE_INFO_VERSION_NAME],
            static::COL_FILE_INFO_TYPE => $item[static::COL_FILE_INFO_TYPE],
            static::COL_FILE_INFO_CREATED_AT => $createdAt,
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
            static::COL_FILE_INFO_VERSION_NAME => 'Version',
            static::COL_FILE_INFO_TYPE => 'File type',
            static::COL_FILE_INFO_CREATED_AT => 'Date',
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
            static::COL_FILE_INFO_CREATED_AT,
            static::COL_FILE_INFO_VERSION_NAME,
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
            static::COL_FILE_INFO_CREATED_AT,
            static::COL_FILE_INFO_VERSION_NAME,
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
}
