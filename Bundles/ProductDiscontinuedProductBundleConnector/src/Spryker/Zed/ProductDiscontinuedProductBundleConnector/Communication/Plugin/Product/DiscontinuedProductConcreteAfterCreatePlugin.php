<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductConcretePluginCreateInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorFacadeInterface getFacade()
 */
class DiscontinuedProductConcreteAfterCreatePlugin extends AbstractPlugin implements ProductConcretePluginCreateInterface
{
    /**
     * {@inheritDoc}
     * - Marks product bundle as discontinued if one of bundled products is discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function create(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFacade()->markProductBundleAsDiscontinuedByBundledProducts($productConcreteTransfer);
    }
}
