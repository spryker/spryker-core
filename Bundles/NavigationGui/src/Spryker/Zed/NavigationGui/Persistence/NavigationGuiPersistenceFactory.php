<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery;
use Orm\Zed\Navigation\Persistence\SpyNavigationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\NavigationGui\NavigationGuiConfig getConfig()
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiQueryContainer getQueryContainer()
 */
class NavigationGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function createNavigationQuery()
    {
        return SpyNavigationQuery::create();
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributesQuery
     */
    public function createCmsPageLocalizedAttributesQuery()
    {
        return SpyCmsPageLocalizedAttributesQuery::create();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function createCategoryAttributeQuery()
    {
        return SpyCategoryAttributeQuery::create();
    }
}
