<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCollector\Persistence\Collector\Storage\Propel;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockStoreTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CmsBlockCollectorQuery extends AbstractPropelCollectorQuery
{
    const COL_IS_ACTIVE = 'is_active';
    const COL_VALID_FROM = 'valid_from';
    const COL_VALID_TO = 'valid_to';
    const COL_NAME = 'name';
    const COL_ID_CMS_BLOCK = 'id_cms_block';
    const COL_PLACEHOLDERS = 'placeholders';
    const COL_GLOSSARY_KEYS = 'glossary_keys';
    const COL_TEMPLATE_PATH = 'template_path';
    const COL_IS_IN_STORE = 'is_in_store';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            SpyCmsBlockTableMap::COL_FK_TEMPLATE,
            SpyCmsBlockTemplateTableMap::COL_ID_CMS_BLOCK_TEMPLATE,
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
            SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK,
            Criteria::INNER_JOIN
        );
        $this->touchQuery->addJoin(
            SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY,
            SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(
            'GROUP_CONCAT(' . SpyCmsBlockGlossaryKeyMappingTableMap::COL_PLACEHOLDER . ')',
            static::COL_PLACEHOLDERS
        );
        $this->touchQuery->withColumn(
            'GROUP_CONCAT(' . SpyGlossaryKeyTableMap::COL_KEY . ')',
            static::COL_GLOSSARY_KEYS
        );
        $this->touchQuery->withColumn(
            SpyCmsBlockTemplateTableMap::COL_TEMPLATE_PATH,
            static::COL_TEMPLATE_PATH
        );

        $this->touchQuery->addJoin(
            [
                SpyCmsBlockTableMap::COL_ID_CMS_BLOCK,
                SpyCmsBlockStoreTableMap::COL_FK_STORE,
            ],
            [
                SpyCmsBlockStoreTableMap::COL_FK_CMS_BLOCK,
                $this->storeTransfer->getIdStore(),
            ],
            Criteria::LEFT_JOIN
        );

        $this->touchQuery->addAnd(SpyCmsBlockTableMap::COL_IS_ACTIVE, true);

        $this->touchQuery->withColumn(SpyCmsBlockStoreTableMap::COL_FK_STORE, static::COL_IS_IN_STORE);
        $this->touchQuery->withColumn(SpyCmsBlockTableMap::COL_IS_ACTIVE, static::COL_IS_ACTIVE);
        $this->touchQuery->withColumn(SpyCmsBlockTableMap::COL_VALID_FROM, static::COL_VALID_FROM);
        $this->touchQuery->withColumn(SpyCmsBlockTableMap::COL_VALID_TO, static::COL_VALID_TO);
        $this->touchQuery->withColumn(SpyCmsBlockTableMap::COL_NAME, static::COL_NAME);
        $this->touchQuery->withColumn(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK, static::COL_ID_CMS_BLOCK);
        $this->touchQuery->addGroupByColumn(SpyCmsBlockTemplateTableMap::COL_TEMPLATE_PATH);
        $this->touchQuery->addGroupByColumn(SpyCmsBlockTableMap::COL_ID_CMS_BLOCK);
    }
}
