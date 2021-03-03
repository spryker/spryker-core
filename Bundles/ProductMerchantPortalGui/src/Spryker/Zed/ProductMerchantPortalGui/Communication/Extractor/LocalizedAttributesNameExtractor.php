<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;

class LocalizedAttributesNameExtractor implements LocalizedAttributesNameExtractorInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    public function extractLocalizedAttributesName(
        ArrayObject $localizedAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): ?string {
        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $localeFromLocalizedAttributes = $localizedAttributesTransfer->getLocale();
            if (!$localeFromLocalizedAttributes) {
                continue;
            }

            if ($localeFromLocalizedAttributes->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributesTransfer->getName();
            }
        }

        return null;
    }
}
