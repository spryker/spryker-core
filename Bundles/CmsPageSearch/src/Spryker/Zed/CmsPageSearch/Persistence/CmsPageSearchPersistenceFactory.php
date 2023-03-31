<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Persistence;

use Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearchQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\CmsPageSearch\CmsPageSearchDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CmsPageSearch\CmsPageSearchConfig getConfig()
 * @method \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface getQueryContainer()
 */
class CmsPageSearchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\CmsPageSearch\Persistence\SpyCmsPageSearchQuery
     */
    public function createSpyCmsPageSearchQuery()
    {
        return SpyCmsPageSearchQuery::create();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function getLocalePropelQuery(): SpyLocaleQuery
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::PROPEL_QUERY_LOCALE);
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\QueryContainer\CmsPageSearchToCmsQueryContainerInterface
     */
    public function getCmsQueryContainer()
    {
        return $this->getProvidedDependency(CmsPageSearchDependencyProvider::QUERY_CONTAINER_CMS_PAGE);
    }
}
