<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlCollector\Persistence\Collector\Propel;

use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use ReflectionClass;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class UrlCollectorQuery extends AbstractPropelCollectorQuery
{
    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $columns = $this->getResourceColumnNames();

        foreach ($columns as $column) {
            $this->touchQuery->withColumn($column, str_replace(SpyUrlTableMap::TABLE_NAME . '.', '', $column));
        }

        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyUrlTableMap::COL_ID_URL,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->addJoin(
            SpyUrlTableMap::COL_FK_LOCALE,
            SpyLocaleTableMap::COL_ID_LOCALE,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(SpyUrlTableMap::COL_URL, UrlTransfer::URL);
    }

    /**
     * @return array
     */
    protected function getResourceColumnNames()
    {
        $reflection = new ReflectionClass(SpyUrlTableMap::class);
        $constants = $reflection->getConstants();

        return array_filter($constants, function ($constant) {
            return strpos($constant, 'fk_resource');
        });
    }

    /**
     * @param string $constantName
     *
     * @return mixed
     */
    protected function getConstantValue($constantName)
    {
        $reflection = new ReflectionClass(SpyUrlTableMap::class);

        return $reflection->getConstant($constantName);
    }
}
