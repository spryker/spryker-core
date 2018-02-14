<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageBusinessFactory getFactory()
 */
class ProductGroupStorageFacade extends AbstractFacade implements ProductGroupStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createProductGroupStorageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds)
    {
        $this->getFactory()->createProductGroupStorageWriter()->unpublish($productAbstractIds);
    }
}
