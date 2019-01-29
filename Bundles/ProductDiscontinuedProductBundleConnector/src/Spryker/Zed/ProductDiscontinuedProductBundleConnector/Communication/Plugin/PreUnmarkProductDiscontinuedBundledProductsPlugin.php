<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PreUnmarkProductDiscontinuedPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 */
class PreUnmarkProductDiscontinuedBundledProductsPlugin extends AbstractPlugin implements PreUnmarkProductDiscontinuedPluginInterface
{
    /**
     * {@inheritdoc}
     * Specification:
     * - Checks bundled products.
     * - Returns response transfer with success true if all bundled products are not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function execute(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ProductDiscontinuedResponseTransfer
    {
        return $this->getFacade()->checkBundledProducts($productDiscontinuedTransfer);
    }
}
