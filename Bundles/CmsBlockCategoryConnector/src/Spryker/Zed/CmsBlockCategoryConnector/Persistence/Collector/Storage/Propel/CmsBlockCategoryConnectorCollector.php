<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryPositionTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class CmsBlockCategoryConnectorCollector extends AbstractPropelCollectorQuery
{
    public const COL_POSITIONS = 'positions';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY,
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            SpyCmsBlockCategoryConnectorTableMap::COL_FK_CMS_BLOCK,
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            [SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY_TEMPLATE],
            [SpyCategoryTableMap::COL_ID_CATEGORY, SpyCategoryTableMap::COL_FK_CATEGORY_TEMPLATE],
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            SpyCmsBlockCategoryConnectorTableMap::COL_FK_CMS_BLOCK_CATEGORY_POSITION,
            SpyCmsBlockCategoryPositionTableMap::COL_ID_CMS_BLOCK_CATEGORY_POSITION,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(
            'GROUP_CONCAT(concat(lower(' . SpyCmsBlockCategoryPositionTableMap::COL_NAME . '), \':\' ,' . SpyCmsBlockTableMap::COL_NAME . '))',
            static::COL_POSITIONS
        );

        $this->touchQuery->addGroupByColumn(SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY);
    }
}
