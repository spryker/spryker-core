<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\ProductAbstract;

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

        $defaultPriceType = $this->getFacade()->getDefaultPriceTypeName();
        $priceTypes = $this->getFacade()->getPriceTypeValues();

        foreach ($priceTypes as $priceType) {
            $priceProductTransfer = $this->getFacade()->findProductAbstractPrice(
                $productAbstractTransfer->getIdProductAbstract(),
                $priceType
            );

            if (!$priceProductTransfer) {
                continue;
            }

            if ($priceProductTransfer->getPriceTypeName() === $defaultPriceType) {
                $productAbstractTransfer->setPrice($priceProductTransfer);
            }

            $productAbstractTransfer->addPrices($priceProductTransfer);
        }

        return $productAbstractTransfer;
    }

}
