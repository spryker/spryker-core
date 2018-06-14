<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductListGui\ProductListGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 */
class ProductListGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function getCategoryAttributeQuery()
    {
        return $this->getProvidedDependency(ProductListGuiDependencyProvider::QUERY_CATEGORY_ATTRIBUTE);
    }
}
