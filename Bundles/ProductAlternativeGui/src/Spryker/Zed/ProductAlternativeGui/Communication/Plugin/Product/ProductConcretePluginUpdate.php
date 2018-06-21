<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeGui\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeGui\Communication\ProductAlternativeGuiCommunicationFactory getFactory()
 */
class ProductConcretePluginUpdate extends AbstractPlugin implements ProductConcretePluginUpdateInterface
{
    /**
     * Specification:
     *  - Calls product alternatives create facade call on product concrete save.
     *
     * @api
     *
     * @see \Spryker\Zed\Product\ProductDependencyProvider
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer)
    {
        if (!$productConcreteTransfer->getProductAlternativeCreateRequests()) {
            return $productConcreteTransfer;
        }

        return $this->getFactory()
            ->getProductAlternativeFacade()
            ->persistProductAlternative($productConcreteTransfer);
    }
}
