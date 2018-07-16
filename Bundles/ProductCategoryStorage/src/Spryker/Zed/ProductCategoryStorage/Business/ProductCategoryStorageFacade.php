<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductCategoryStorage\Business\ProductCategoryStorageBusinessFactory getFactory()
 */
class ProductCategoryStorageFacade extends AbstractFacade implements ProductCategoryStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createProductCategoryStorageWriter()->publish($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()->createProductCategoryStorageWriter()->unpublish($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $categoryIds
     *
     * @return array
     */
    public function getRelatedCategoryIds(array $categoryIds)
    {
        return $this->getFactory()->createProductCategoryStorageWriter()->getRelatedCategoryIds($categoryIds);
    }
}
