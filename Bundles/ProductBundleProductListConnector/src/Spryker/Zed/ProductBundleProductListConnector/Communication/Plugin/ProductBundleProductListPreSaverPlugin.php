<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreSaverInterface;

/**
 * @method \Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig getConfig()
 */
class ProductBundleProductListPreSaverPlugin extends AbstractPlugin implements ProductListPreSaverInterface
{
    /**
     * {@inheritdoc}
     * - Expands blacklist product list with bundle product if assigned product is added.
     * - Expands whitelist product list with assigned products if bundle id added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function preSave(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        return $this->getFacade()->expandProductBundle($productListResponseTransfer);
    }
}
