<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Communication\Plugin\ProductAbstract;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginUpdateInterface;

/**
 * @method \Spryker\Zed\PriceProduct\Business\PriceProductFacade getFacade()
 * @method \Spryker\Zed\PriceProduct\Communication\PriceProductCommunicationFactory getFactory()
 */
class PriceProductAbstractAfterUpdatePlugin extends AbstractPlugin implements ProductAbstractPluginUpdateInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function update(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFacade()->persistProductAbstractPriceCollection($productAbstractTransfer);
    }
}
