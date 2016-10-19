<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\ProductConcrete;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface;

/**
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 */
class PriceProductConcreteAfterCreatePlugin extends AbstractPlugin implements ProductConcretePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function run(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFacade()->persistProductConcretePrice($productConcreteTransfer);
    }
}
