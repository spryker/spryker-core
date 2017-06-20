<?php

namespace Spryker\Zed\CmsBlockProductConnector\Persistence\Collector\Storage;


use Orm\Zed\Cms\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockProductConnector\Persistence\Map\SpyCmsBlockProductConnectorTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

abstract class AbstractCmsBlockProductConnectorCollector extends AbstractPropelCollectorQuery
{
    const COL_CMS_BLOCK_NAMES = 'cms_block_names';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyCmsBlockProductConnectorTableMap::COL_FK_PRODUCT_ABSTRACT,
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            SpyCmsBlockProductConnectorTableMap::COL_FK_CMS_BLOCK,
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(
            'GROUP_CONCAT(' . SpyCmsBlockTableMap::COL_NAME . ')',
            static::COL_CMS_BLOCK_NAMES
        );

        $this->touchQuery->addGroupByColumn(SpyCmsBlockProductConnectorTableMap::COL_FK_PRODUCT_ABSTRACT);
    }
}