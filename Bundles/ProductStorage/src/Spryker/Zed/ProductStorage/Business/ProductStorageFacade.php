<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business;

use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductStorage\Business\ProductStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface getRepository()
 */
class ProductStorageFacade extends AbstractFacade implements ProductStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function publishAbstractProducts(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractStorageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductAbstracts(array $productAbstractIds)
    {
        $this->getFactory()->createProductAbstractStorageWriter()->unpublish($productAbstractIds);
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
    public function publishConcreteProducts(array $productIds)
    {
        $this->getFactory()->createProductConcreteStorageWriter()->publish($productIds);
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
    public function unpublishConcreteProducts(array $productIds)
    {
        $this->getFactory()->createProductConcreteStorageWriter()->unpublish($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractStorageTransfer $productAbstractStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function expandWithAttributeVariantCollection(
        ProductAbstractStorageTransfer $productAbstractStorageTransfer
    ): ProductAbstractStorageTransfer {
        return $this->getFactory()
            ->createProductAbstractStorageExpander()
            ->expandWithAttributeVariantCollection($productAbstractStorageTransfer);
    }
}
