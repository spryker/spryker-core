<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\Business\ProductQuantityStorageBusinessFactory getFactory()
 */
class ProductQuantityStorageFacade extends AbstractFacade implements ProductQuantityStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishProductQuantity(array $productIds): void
    {
        $this->getFactory()->createProductQuantityStorageWriter()->publish($productIds);
    }
}
