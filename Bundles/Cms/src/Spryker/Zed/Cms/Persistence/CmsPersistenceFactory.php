<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\Base\SpyCmsTemplateQuery;
use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainer getQueryContainer()
 */
class CmsPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function createCmsTemplateQuery()
    {
        return SpyCmsTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function createCmsPageQuery()
    {
        return SpyCmsPageQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlockQuery
     */
    public function createCmsBlockQuery()
    {
        return SpyCmsBlockQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function createCmsGlossaryKeyMappingQuery()
    {
        return SpyCmsGlossaryKeyMappingQuery::create();
    }

}
