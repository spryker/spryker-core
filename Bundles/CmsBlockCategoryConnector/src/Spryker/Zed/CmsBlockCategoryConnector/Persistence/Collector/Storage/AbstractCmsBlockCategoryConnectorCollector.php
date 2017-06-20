<?php

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

abstract class AbstractCmsBlockCategoryConnectorCollector extends AbstractPropelCollectorQuery
{

    const COL_CMS_BLOCK_NAMES = 'cms_block_names';

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


        $this->touchQuery->withColumn(
            'GROUP_CONCAT(' . SpyCmsBlockTableMap::COL_NAME . ')',
            static::COL_CMS_BLOCK_NAMES)
        ;

        $this->touchQuery->addGroupByColumn(SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY);
    }


}