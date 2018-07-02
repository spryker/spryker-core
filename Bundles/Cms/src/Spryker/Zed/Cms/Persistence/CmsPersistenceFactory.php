<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Orm\Zed\Cms\Persistence\SpyCmsVersionQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Cms\CmsDependencyProvider;
use Spryker\Zed\Cms\Persistence\Mapper\CmsMapper;
use Spryker\Zed\Cms\Persistence\Mapper\CmsMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 * @method \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface getQueryContainer()
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
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersionQuery
     */
    public function createSpyCmsVersionQuery()
    {
        return SpyCmsVersionQuery::create();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function createUrlQuery()
    {
        return SpyUrlQuery::create();
    }

    /**
     * @return \Spryker\Zed\Cms\Persistence\Mapper\CmsMapperInterface
     */
    public function createCmsMapper(): CmsMapperInterface
    {
        return new CmsMapper();
    }
}
