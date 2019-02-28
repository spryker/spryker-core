<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business;

use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorBusinessFactory getFactory()
 */
class ProductBundleProductListConnectorFacade extends AbstractFacade implements ProductBundleProductListConnectorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        return $this->getFactory()
            ->createProductListExpander()
            ->expandProductBundle($productListResponseTransfer);
    }
}
