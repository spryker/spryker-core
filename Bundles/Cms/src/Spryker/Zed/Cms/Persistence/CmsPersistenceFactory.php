<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;

/**
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\Cms\Persistence\CmsEntityManagerInterface getEntityManager()
 */
class CmsPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery
     */
    public function createCmsTemplateQuery(): SpyCmsTemplateQuery
    {
        return SpyCmsTemplateQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function createCmsPageQuery(): SpyCmsPageQuery
    {
        return SpyCmsPageQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery
     */
    public function createCmsGlossaryKeyMappingQuery(): SpyCmsGlossaryKeyMappingQuery
    {
        return SpyCmsGlossaryKeyMappingQuery::create();
    }

    /**
     * @return \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    public function createLocaleQuery(): LocaleQueryContainerInterface
    {
        return $this->getProvidedDependency(CmsDependencyProvider::QUERY_CONTAINER_LOCALE);
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function createCmsPageLocalizedAttributesQuery(): SpyCmsPageLocalizedAttributesQuery
    {
        return SpyCmsPageLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function createSpyCmsVersionQuery(): SpyCmsVersionQuery
    {
        return SpyCmsVersionQuery::create();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function createUrlQuery(): SpyUrlQuery
    {
        return SpyUrlQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery
     */
    public function createCmsPageStoreQuery(): SpyCmsPageStoreQuery
    {
        return SpyCmsPageStoreQuery::create();
    }
}
