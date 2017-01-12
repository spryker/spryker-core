<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginReadInterface;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleFacade getFacade()
 * @method \Spryker\Zed\ProductBundle\Communication\ProductBundleCommunicationFactory getFactory()
 */
class ProductBundleProductConcreteReadPlugin extends AbstractPlugin implements ProductConcretePluginReadInterface
{

    /**
     * Specification:
     * - Executed after a persisted concrete product is read from database.
     * - Can be used for extending the ProductAbstractTransfer with some extra information or execute any other logic.
     * - To inject instances of the plugin @see \Spryker\Zed\Product\ProductDependencyProvider.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function read(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFacade()->assignBundledProductsToProductConcrete($productConcreteTransfer);
    }

}
