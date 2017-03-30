<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageVersionQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Spryker\Zed\Cms\CmsDependencyProvider;
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

    /**
     * @return \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    public function createLocaleQuery()
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_LOCALE);
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function createCmsPageLocalizedAttributesQuery()
    {
        return SpyCmsPageLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageVersionQuery
     */
    public function createSpyCmsPageVersionQuery()
    {
        return SpyCmsPageVersionQuery::create();
    }


}
