<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin\ProductBundle;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductBundleExtension\Dependency\Plugin\ProductBundlePostSavePluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 */
class DiscontinuedProductBundlePostSavePlugin extends AbstractPlugin implements ProductBundlePostSavePluginInterface
{
    /**
     * {@inheritdoc}
     * - Marks product bundle as discontinued if one of bundled products is discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function execute(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $this->getFacade()->markProductBundleAsDiscontinuedByBundledProducts($productConcreteTransfer);
    }
}
