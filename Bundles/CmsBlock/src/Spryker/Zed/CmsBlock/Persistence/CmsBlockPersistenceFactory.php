<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery;
use Spryker\Zed\CmsBlock\Persistence\Mapper\CmsBlockMapper;
use Spryker\Zed\CmsBlock\Persistence\Mapper\CmsBlockMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsBlock\CmsBlockConfig getConfig()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsBlock\Persistence\CmsBlockRepositoryInterface getRepository()
 */
class CmsBlockPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    public function createCmsBlockQuery(): SpyCmsBlockQuery
    {
        return SpyCmsBlockQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMappingQuery
     */
    public function createCmsBlockGlossaryKeyMappingQuery(): SpyCmsBlockGlossaryKeyMappingQuery
    {
        return SpyCmsBlockGlossaryKeyMappingQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplateQuery
     */
    public function createCmsBlockTemplateQuery(): SpyCmsBlockTemplateQuery
    {
        return SpyCmsBlockTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockStoreQuery
     */
    public function createCmsBlockStoreQuery(): SpyCmsBlockStoreQuery
    {
        return SpyCmsBlockStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Persistence\Mapper\CmsBlockMapperInterface
     */
    public function createCmsBlockMapper(): CmsBlockMapperInterface
    {
        return new CmsBlockMapper();
    }
}
