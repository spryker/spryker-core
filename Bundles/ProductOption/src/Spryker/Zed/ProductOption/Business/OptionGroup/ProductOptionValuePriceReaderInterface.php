<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ProductOptionValueStorePricesRequestTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;

interface ProductOptionValuePriceReaderInterface
{
    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     * @param string|null $currencyCode
     *
     * @return int|null
     */
    public function getCurrentGrossPrice(SpyProductOptionValue $productOptionValueEntity, ?string $currencyCode = null);

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     * @param string|null $currencyCode
     *
     * @return int|null
     */
    public function getCurrentNetPrice(SpyProductOptionValue $productOptionValueEntity, ?string $currencyCode = null);

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

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param string|null $priceMode
     *
     * @return int|null
     */
    public function resolveUnitPrice(ProductOptionTransfer $productOptionTransfer, ?string $priceMode): ?int;
}
