<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductRelationStorage\Business\ProductRelationStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface getRepository()
 */
class ProductRelationStorageFacade extends AbstractFacade implements ProductRelationStorageFacadeInterface
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
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createProductRelationStorageWriter()->publish($productAbstractIds);
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
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()->createProductRelationStorageWriter()->unpublish($productAbstractIds);
    }
}
