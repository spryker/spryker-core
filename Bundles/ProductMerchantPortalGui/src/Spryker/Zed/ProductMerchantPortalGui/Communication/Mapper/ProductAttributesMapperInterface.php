<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Symfony\Component\Form\FormErrorIterator;

interface ProductAttributesMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormErrorIterator<\Symfony\Component\Form\FormError> $errors
     * @param array<string, array<string, mixed>> $attributesInitialData
     *
     * @return array<array<string>>
     */
    public function mapErrorsToAttributesData(FormErrorIterator $errors, array $attributesInitialData): array;

    /**
     * @param array<array<array<string>>> $attributesInitialData
     * @param array<string> $attributes
     *
     * @return array<string>
     */
    public function mapAttributesDataToProductAttributes(array $attributesInitialData, array $attributes): array;

    /**
     * @param array<array<array<string>>> $attributesInitialData
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function mapAttributesDataToLocalizedAttributesTransfers(array $attributesInitialData, ArrayObject $localizedAttributesTransfers): ArrayObject;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $destinationLocalizedAttributesTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $sourceLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function mapLocalizedAttributesNames(
        ArrayObject $destinationLocalizedAttributesTransfers,
        ArrayObject $sourceLocalizedAttributesTransfers
    ): ArrayObject;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $destinationLocalizedAttributesTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $sourceLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     */
    public function mapLocalizedDescriptions(
        ArrayObject $destinationLocalizedAttributesTransfers,
        ArrayObject $sourceLocalizedAttributesTransfers
    ): ArrayObject;
}
