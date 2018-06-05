<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\Business\ProductPackagingUnitStorageBusinessFactory getFactory()
 */
class ProductPackagingUnitStorageFacade extends AbstractFacade implements ProductPackagingUnitStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function publishProductAbstractPackaging(array $productConcreteIds)
    {
        $this->getFactory()->createProductAbstractPackagingStorageWriter()->publish($productConcreteIds);
    }
}
