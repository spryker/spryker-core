<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Communication\Plugin\ProductList;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListExtension\Dependency\Plugin\ProductListPreUpdatePluginInterface;

/**
 * @method \Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorConfig getConfig()
 */
class ProductBundleProductListPreUpdatePlugin extends AbstractPlugin implements ProductListPreUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands blacklist product list with bundle product if assigned product is added.
     * - Expands whitelist product list with assigned products if bundle id added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function execute(ProductListTransfer $productListTransfer): ProductListResponseTransfer
    {
        return $this->getFacade()->expandProductListWithProductBundle($productListTransfer);
    }
}
