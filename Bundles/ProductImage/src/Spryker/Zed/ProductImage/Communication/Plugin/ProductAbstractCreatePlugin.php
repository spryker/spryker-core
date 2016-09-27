<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacade getFacade()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 */
class ProductAbstractCreatePlugin extends AbstractPlugin implements ProductAbstractPluginInterface
{

    /**
     * TODO add return transfer
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function run(ProductAbstractTransfer $productConcreteTransfer)
    {
        $this->getFacade()->runProductAbstractCreatePlugin($productConcreteTransfer);
    }

}
