<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageBusinessFactory getFactory()
 */
class PriceProductStorageFacade extends AbstractFacade implements PriceProductStorageFacadeInterface
{
    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function publishPriceProductConcrete(array $productConcreteIds)
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()->publish($productConcreteIds);
    }

    /**
     * @api
     *
     * @param array $productConcreteIds
     *
     * @return void
     */
    public function unpublishPriceProductConcrete(array $productConcreteIds)
    {
        $this->getFactory()->createPriceProductConcreteStorageWriter()->unpublish($productConcreteIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishPriceProductAbstract(array $productAbstractIds)
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()->publish($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishPriceProductAbstract(array $productAbstractIds)
    {
        $this->getFactory()->createPriceProductAbstractStorageWriter()->unpublish($productAbstractIds);
    }
}
