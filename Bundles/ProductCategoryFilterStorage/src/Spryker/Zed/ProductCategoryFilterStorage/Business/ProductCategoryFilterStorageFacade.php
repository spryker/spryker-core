<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategoryFilterStorage\Business\ProductCategoryFilterStorageBusinessFactory getFactory()
 */
class ProductCategoryFilterStorageFacade extends AbstractFacade implements ProductCategoryFilterStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds)
    {
        $this->getFactory()->createProductCategoryFilterStorageWriter()->publish($categoryIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublish(array $categoryIds)
    {
        $this->getFactory()->createProductCategoryFilterStorageWriter()->unpublish($categoryIds);
    }
}
