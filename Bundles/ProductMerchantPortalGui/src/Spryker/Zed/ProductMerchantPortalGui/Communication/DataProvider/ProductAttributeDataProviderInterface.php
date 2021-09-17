<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;

interface ProductAttributeDataProviderInterface
{
    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param array<string> $attributes
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @return array<string[]>
     */
    public function getData(array $attributes, ArrayObject $localizedAttributeTransfers): array;

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    public function findLocalizedAttribute(ArrayObject $localizedAttributes, int $idLocale): ?LocalizedAttributesTransfer;

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    public function findLocalizedAttributeByLocaleName(ArrayObject $localizedAttributesTransfers, string $localeName): ?LocalizedAttributesTransfer;
}
