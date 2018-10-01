<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationCollector\Persistence\Collector\Propel;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class NavigationMenuCollectorQuery extends AbstractPropelCollectorQuery
{
    public const FIELD_ID_NAVIGATION = 'id_navigation';
    public const FIELD_NAVIGATION_KEY = 'navigation_key';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyNavigationTableMap::COL_ID_NAVIGATION,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(SpyNavigationTableMap::COL_ID_NAVIGATION, self::FIELD_ID_NAVIGATION);
        $this->touchQuery->withColumn(SpyNavigationTableMap::COL_KEY, self::FIELD_NAVIGATION_KEY);
    }
}
