<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Dependency\Facade;

use Generated\Shared\Transfer\ProductImageSetTransfer;

interface ConfigurableBundleToProductImageFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    public function deleteProductImageSet(ProductImageSetTransfer $productImageSetTransfer);
}
