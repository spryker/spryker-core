<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

class ProductAbstractNameBuilder implements ProductAbstractNameBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    public function buildProductAbstractName(
        ProductAbstractTransfer $productAbstractTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        $localizedAttributeTransfers = $productAbstractTransfer->getLocalizedAttributes();

        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $localeFromLocalizedAttributes = $localizedAttributesTransfer->getLocale();
            if (!$localeFromLocalizedAttributes) {
                continue;
            }

            if ($localeFromLocalizedAttributes->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributesTransfer->getName();
            }
        }

        return $productAbstractTransfer->getName();
    }
}
