<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageRepositoryInterface getRepository()
 */
class ProductImageStorageFacade extends AbstractFacade implements ProductImageStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductAbstractImages(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractImageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstractImages(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractImageWriter()->unpublish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function publishProductConcreteImages(array $productIds)
    {
        $this->getFactory()->createProductConcreteImageWriter()->publish($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productIds
     *
     * @return void
     */
    public function unpublishProductConcreteImages(array $productIds)
    {
        $this->getFactory()->createProductConcreteImageWriter()->unpublish($productIds);
    }
}
