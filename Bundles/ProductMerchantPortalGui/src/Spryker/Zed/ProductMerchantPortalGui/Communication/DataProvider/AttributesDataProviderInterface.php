<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;

interface AttributesDataProviderInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return array<array<string, mixed>>
     */
    public function getProductAttributesData(array $productManagementAttributeTransfers): array;

    /**
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<array<string, mixed>>
     */
    public function getExistingConcreteProductData(
        MerchantProductTransfer $merchantProductTransfer,
        array $productManagementAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): array;
}
