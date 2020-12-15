<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Persistence;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery;
use Spryker\Zed\CategoryGui\CategoryGuiDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 */
class CategoryGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryQuery
     */
    public function getCategoryPropelQuery(): SpyCategoryQuery
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PROPEL_QUERY_CATEGORY);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery
     */
    public function getCategoryTemplatePropelQuery(): SpyCategoryTemplateQuery
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PROPEL_QUERY_CATEGORY_TEMPLATE);
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    public function getCategoryNodePropelQuery(): SpyCategoryNodeQuery
    {
        return $this->getProvidedDependency(CategoryGuiDependencyProvider::PROPEL_QUERY_CATEGORY_NODE);
    }
}
