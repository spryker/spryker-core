<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\ProductAbstract;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginReadInterface;

/**
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 * @method \Spryker\Zed\Price\Communication\PriceCommunicationFactory getFactory()
 */
class PriceProductAbstractReadPlugin extends AbstractPlugin implements ProductAbstractPluginReadInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function read(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAbstractTransfer->requireIdProductAbstract();

        $priceProductTransfer = $this->getFacade()->findProductAbstractPrice($productAbstractTransfer->getIdProductAbstract());
        if ($priceProductTransfer) {
            $productAbstractTransfer->setPrice($priceProductTransfer);
        }

        $priceProductTransfers = $this->getFacade()->findProductAbstractPrices($productAbstractTransfer->getIdProductAbstract());
        if ($priceProductTransfers) {
            $productAbstractTransfer->setPrices(new ArrayObject($priceProductTransfers));
        }

        return $productAbstractTransfer;
    }
}
