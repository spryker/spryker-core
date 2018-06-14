<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;

interface ProductOptionValuePriceReaderInterface
{
    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     *
     * @return int|null
     */
    public function getCurrentGrossPrice(SpyProductOptionValue $productOptionValueEntity);

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     *
     * @return int|null
     */
    public function getCurrentNetPrice(SpyProductOptionValue $productOptionValueEntity);

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getStorePrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer);

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueStorePricesResponseTransfer
     */
    public function getAllPrices(ProductOptionValueStorePricesRequestTransfer $storePricesRequestTransfer);
}
