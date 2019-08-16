<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Table;

use Orm\Zed\Store\Persistence\SpyStore;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StoreTable extends AbstractTable
{
    public const COL_ID_STORE = 'id_store';
    public const COL_NAME = 'name';

    public const IDENTIFIER = 'store_data_table';

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected $storeQuery;

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery $storeQuery
     */
    public function __construct(SpyStoreQuery $storeQuery)
    {
        $this->storeQuery = $storeQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::COL_ID_STORE => 'Store ID',
            static::COL_NAME => 'Name',
        ]);

        $config->setDefaultSortField(static::COL_ID_STORE);

        $config->setSortable([
            static::COL_ID_STORE,
            static::COL_NAME,
        ]);

        $config->setSearchable([
            static::COL_NAME,
        ]);

        $this->setTableIdentifier(static::IDENTIFIER);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $queryResults = $this->runQuery($this->storeQuery, $config, true);

        $storeCollection = [];
        foreach ($queryResults as $storeEntity) {
            $storeCollection[] = $this->generateItem($storeEntity);
        }

        return $storeCollection;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     *
     * @return array
     */
    protected function generateItem(SpyStore $storeEntity): array
    {
        return [
            static::COL_ID_STORE => $storeEntity->getName(),
            static::COL_NAME => $storeEntity->getName(),
        ];
    }
}
