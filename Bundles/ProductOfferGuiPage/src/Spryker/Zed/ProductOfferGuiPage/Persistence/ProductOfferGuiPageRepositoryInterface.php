<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;

interface ProductOfferGuiPageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\MerchantUserTransfer|null $merchantUserTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getConcreteProductsForProductListTable(
        ProductListTableCriteriaTransfer $productListTableCriteriaTransfer,
        LocaleTransfer $localeTransfer,
        ?MerchantUserTransfer $merchantUserTransfer
    ): ProductConcreteCollectionTransfer;
}
