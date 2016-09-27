<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacade getFacade()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 */
class ProductConcreteCreatePlugin extends AbstractPlugin implements ProductConcretePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function run(ProductConcreteTransfer $productConcreteTransfer)
    {
        $this->getFacade()->runProductConcreteCreatePlugin($productConcreteTransfer);
    }

}
