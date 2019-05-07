<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Table;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class UrlTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'url_table';

    /**
     * @var \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    protected $urlQuery;

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrlQuery $urlQuery
     */
    public function __construct(SpyUrlQuery $urlQuery)
    {
        $this->urlQuery = $urlQuery;
        $this->defaultUrl = 'url-table';
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => 'Category node Id',
            SpyUrlTableMap::COL_FK_LOCALE => 'Fk Locale',
            SpyUrlTableMap::COL_URL => 'Url',
            SpyUrlTableMap::COL_FK_RESOURCE_REDIRECT => 'Fk Resource Redirect',
        ]);
        $config->setSortable([
            SpyUrlTableMap::COL_URL,
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
        $query = $this->urlQuery;
        $queryResults = $this->runQuery($query, $config);
        $results = [];
        foreach ($queryResults as $attribute) {
            $results[] = [
                SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE => $attribute[SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE],
                SpyUrlTableMap::COL_FK_LOCALE => $attribute['spy_localelocale_name'], //@todo: refactor when table alias is fixed (missing .)
                SpyUrlTableMap::COL_URL => $attribute[SpyUrlTableMap::COL_URL],
                SpyUrlTableMap::COL_FK_RESOURCE_REDIRECT => $attribute[SpyUrlTableMap::COL_FK_RESOURCE_REDIRECT],
            ];
        }
        unset($queryResults);

        return $results;
    }
}
