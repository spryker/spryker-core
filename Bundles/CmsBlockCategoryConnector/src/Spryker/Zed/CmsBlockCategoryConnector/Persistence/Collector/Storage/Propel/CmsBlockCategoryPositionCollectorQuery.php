<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence\Collector\Storage\Propel;


use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryPositionTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CmsBlockCategoryPositionCollectorQuery extends AbstractPropelCollectorQuery
{
    const COL_CMS_BLOCK_NAMES = 'cms_block_names';
    const COL_POSITION_NAME = 'position_name';
    const COL_ID_CATEGORY = 'id_category';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyCmsBlockCategoryConnectorTableMap::COL_FK_CMS_BLOCK_CATEGORY_POSITION,
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
            SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY,
            static::COL_ID_CATEGORY
        );
        $this->touchQuery->withColumn(
            SpyCmsBlockCategoryPositionTableMap::COL_NAME,
            static::COL_POSITION_NAME
        );
        $this->touchQuery->withColumn(
            'GROUP_CONCAT(' . SpyCmsBlockTableMap::COL_NAME . ')',
            static::COL_CMS_BLOCK_NAMES
        );

        $this->touchQuery->addGroupByColumn(SpyCmsBlockCategoryConnectorTableMap::COL_FK_CMS_BLOCK_CATEGORY_POSITION);
    }
}