<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;

class ProductAttributeDataProvider implements ProductAttributeDataProviderInterface
{
    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param array<string> $attributes
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @return array<array<string>>
     */
    public function getData(array $attributes, ArrayObject $localizedAttributeTransfers): array
    {
        $data = [];

        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $data = $this->addLocalizedAttributes($localizedAttributesTransfer, $data);
        }

        foreach ($attributes as $attributeName => $value) {
            if (!isset($data[$attributeName])) {
                $data[$attributeName] = [
                    ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME => $attributeName,
                ];
            }

            $data[$attributeName][ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT] = $value;
        }

        ksort($data);

        return array_values($data);
    }

    /**
     * @phpstan-param \ArrayObject<int,\Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributes
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    public function findLocalizedAttribute(ArrayObject $localizedAttributes, int $idLocale): ?LocalizedAttributesTransfer
    {
        foreach ($localizedAttributes as $localizedAttribute) {
            if ($localizedAttribute->getLocaleOrFail()->getIdLocaleOrFail() === $idLocale) {
                return $localizedAttribute;
            }
        }

        return null;
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    public function findLocalizedAttributeByLocaleName(ArrayObject $localizedAttributesTransfers, string $localeName): ?LocalizedAttributesTransfer
    {
        foreach ($localizedAttributesTransfers as $localizedAttribute) {
            if ($localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail() === $localeName) {
                return $localizedAttribute;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     * @param array<array<string>> $data
     *
     * @return array<array<string>>
     */
    protected function addLocalizedAttributes(
        LocalizedAttributesTransfer $localizedAttributesTransfer,
        array $data
    ): array {
        foreach ($localizedAttributesTransfer->getAttributes() as $attributeName => $value) {
            if (!isset($data[$attributeName])) {
                $data[$attributeName] = [
                    ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME => $attributeName,
                ];
            }

            $data[$attributeName][$localizedAttributesTransfer->getLocaleOrFail()->getLocaleName()] = $value;
        }

        return $data;
    }
}
