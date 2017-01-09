<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginUpdateInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 */
class ProductBundleProductConcreteAfterUpdatePlugin extends AbstractPlugin implements ProductConcretePluginUpdateInterface
{

    /**
     * Specification:
     * - Executed before and after an concrete product is created.
     * - Can be used for persisting other concrete product related information to database or execute any other logic.
     * - The ID of the concrete product is available only if the plugin executed on "after" event.
     * - To inject instances of the plugin @see \Spryker\Zed\Product\ProductDependencyProvider.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function update(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFacade()->saveBundledProducts($productConcreteTransfer);
    }

}
