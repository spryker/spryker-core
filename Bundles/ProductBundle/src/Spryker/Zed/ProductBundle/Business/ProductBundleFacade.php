<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business;

use Generated\Shared\Transfer\ProductBundleTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundle\Business\ProductBundleBusinessFactory getFactory()
 */
class ProductBundleFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\ProductBundleTransfer $productBundleTransfer
     */
    public function addProductBundle(ProductBundleTransfer $productBundleTransfer)
    {
        return $this->getFactory()
            ->createProductBundleWriter()
            ->createProductBundle($productBundleTransfer);
    }
}
