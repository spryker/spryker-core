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
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductAbstractPackaging(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductAbstractPackagingStorageWriter()
            ->publish($productAbstractIds);
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
    public function unpublishProductAbstractPackaging(array $productAbstractIds): void
    {
        $this->getFactory()
            ->createProductAbstractPackagingStorageWriter()
            ->unpublish($productAbstractIds);
    }
}
