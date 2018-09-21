<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Communication\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImage\Communication\ProductImageCommunicationFactory getFactory()
 */
class ProductConcreteAfterCreatePlugin extends AbstractPlugin implements ProductConcretePluginCreateInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function create(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFacade()->createProductConcreteImageSetCollection($productConcreteTransfer);
    }
}
