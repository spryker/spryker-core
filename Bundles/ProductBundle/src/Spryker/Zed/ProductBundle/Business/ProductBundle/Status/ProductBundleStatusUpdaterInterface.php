<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Status;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductBundleStatusUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function deactivateRelatedProductBundles(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
