<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Persistence\ProductDiscontinuedStorageRepositoryInterface getRepository()
 */
class ProductDiscontinuedStorageFacade extends AbstractFacade implements ProductDiscontinuedStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function publish(array $productDiscontinuedIds): void
    {
        $this->getFactory()->createProductDiscontinuedPublisher()->publish($productDiscontinuedIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productDiscontinuedIds
     *
     * @return void
     */
    public function unpublish(array $productDiscontinuedIds): void
    {
        $this->getFactory()->createProductDiscontinuedUnpublisher()->unpublish($productDiscontinuedIds);
    }
}
