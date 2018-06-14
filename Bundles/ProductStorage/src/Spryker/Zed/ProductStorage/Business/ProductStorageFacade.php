<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory getFactory()
 */
class ProductStorageFacade extends AbstractFacade implements ProductStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractStorageWriter()->publish($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstracts(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractStorageWriter()->unpublish($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function publishConcreteProducts(array $productIds)
    {
        $this->getFactory()->createProductConcreteStorageWriter()->publish($productIds);
    }

    /**
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishConcreteProducts(array $productIds)
    {
        $this->getFactory()->createProductConcreteStorageWriter()->unpublish($productIds);
    }
}
