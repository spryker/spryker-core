<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;

interface LocalizedAttributesExtractorInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    public function extractLocalizedAttributes(
        ArrayObject $localizedAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): ?LocalizedAttributesTransfer;

    /**
     * @param string[] $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function extractSuperAttributes(
        array $attributes,
        ArrayObject $localizedAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): array;

    /**
     * @param string[] $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
     */
    public function extractCombinedSuperAttributeNames(
        array $attributes,
        ArrayObject $localizedAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): array;
}
