<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin\ProductDiscontinued;

use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\ProductDiscontinuedPreDeleteCheckPluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 */
class BundledProductsProductDiscontinuedPreDeleteCheckPlugin extends AbstractPlugin implements ProductDiscontinuedPreDeleteCheckPluginInterface
{
    /**
     * {@inheritdoc}
     * - Checks discontinued status of bundled products.
     * - Returns ProductDiscontinuedResponseTransfer with isSuccessful=true if all bundled products are not discontinued.
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
