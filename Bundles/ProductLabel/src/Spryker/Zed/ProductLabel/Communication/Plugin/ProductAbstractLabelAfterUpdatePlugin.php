<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface;

/**
 * @method \Spryker\Zed\ProductLabel\Communication\ProductLabelCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelFacade getFacade()
 */
class ProductAbstractLabelAfterUpdatePlugin extends AbstractPlugin implements ProductAbstractPluginUpdateInterface
{
    protected const LABEL_SALE_ID = 5;

    protected const PRICE_TYPE_ORIGINAL = 'ORIGINAL';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function update(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $priceProductFacade = $this->getFactory()->getPriceProductFacade();
        $priceProduct = $priceProductFacade->findPriceBySku($productAbstractTransfer->getSku(), static::PRICE_TYPE_ORIGINAL);

        if ($priceProduct === null) {
            $this->getFacade()->removeProductAbstractRelationsForLabel(
                static::LABEL_SALE_ID,
                [$productAbstractTransfer->getIdProductAbstract()]
            );
        }

        return $productAbstractTransfer;
    }
}
