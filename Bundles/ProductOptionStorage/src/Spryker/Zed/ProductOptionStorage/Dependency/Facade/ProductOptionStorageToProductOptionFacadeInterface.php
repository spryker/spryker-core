<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;

interface ProductOptionStorageToProductOptionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getProductOptionValueStorePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getAllProductOptionValuePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer);
}
