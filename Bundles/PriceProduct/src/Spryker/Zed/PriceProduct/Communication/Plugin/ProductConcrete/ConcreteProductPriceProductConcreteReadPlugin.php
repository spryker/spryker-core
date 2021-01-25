<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Communication\Plugin\ProductConcrete;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface;

/**
 * @method \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProduct\Communication\PriceProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProduct\PriceProductConfig getConfig()
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface getQueryContainer()
 */
class ConcreteProductPriceProductConcreteReadPlugin extends AbstractPlugin implements ProductConcretePluginReadInterface
{
    /**
     * {@inheritDoc}
     * - Expands ProductConcreteTransfer with concrete product prices.
     * - Does not merge abstract concrete prices with concrete product prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function read(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFacade()->expandProductConcreteWithPrices($productConcreteTransfer);
    }
}
