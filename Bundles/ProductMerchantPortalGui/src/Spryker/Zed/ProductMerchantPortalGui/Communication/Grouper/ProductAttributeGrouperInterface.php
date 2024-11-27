<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Grouper;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductAttributeGrouperInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return array<string, string>
     */
    public function getLocalizedAttributeNamesGroupedByProductAttributeKey(array $productManagementAttributeTransfers, ?LocaleTransfer $localeTransfer): array;

    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param array<string, mixed> $productAttributes
     *
     * @return array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function getApplicableProductManagementAttributesGroupedByProductAttributeKey(
        array $productManagementAttributeTransfers,
        array $productAttributes
    ): array;

    /**
     * @param array<int|string, mixed> $initialData
     * @param array<string, \Generated\Shared\Transfer\ProductManagementAttributeTransfer> $groupProductManagementAttributeTransfers
     *
     * @return array<string, mixed>
     */
    public function getInitialDataGroupedByAttributeKey(array $initialData, array $groupProductManagementAttributeTransfers): array;
}
