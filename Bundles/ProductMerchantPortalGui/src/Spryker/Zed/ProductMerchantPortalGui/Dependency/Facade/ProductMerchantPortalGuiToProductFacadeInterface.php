<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductMerchantPortalGuiToProductFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return int
     */
    public function saveProductConcrete(ProductConcreteTransfer $productConcreteTransfer);
}
