<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductCategoryFilterGui\Persistence\ProductCategoryFilterGuiPersistenceFactory getFactory()
 */
class ProductCategoryFilterGuiQueryContainer extends AbstractQueryContainer implements ProductCategoryFilterGuiQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery
     */
    public function queryRootNodes()
    {
        return $this->getFactory()->getProductCategoryFilterQueryContainer()->queryRootNodes();
    }
}
