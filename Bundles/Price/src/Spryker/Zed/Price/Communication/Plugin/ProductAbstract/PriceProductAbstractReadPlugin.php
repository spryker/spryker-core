<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\ProductAbstract;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface;

/**
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 */
class PriceProductAbstractReadPlugin extends AbstractPlugin implements ProductAbstractPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function run(ProductAbstractTransfer $productAbstractTransfer)
    {
        $priceProductTransfer = $this->getFacade()
            ->getProductAbstractPrice($productAbstractTransfer->getIdProductAbstract());

        $productAbstractTransfer->setPrice($priceProductTransfer);

        return $productAbstractTransfer;
    }
}
