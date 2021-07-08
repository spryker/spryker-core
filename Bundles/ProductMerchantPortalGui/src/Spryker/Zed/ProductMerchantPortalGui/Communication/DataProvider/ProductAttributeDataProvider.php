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
     * @param string[] $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     *
     * @return string[][]
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
     * @param \Generated\Shared\Transfer\LocalizedAttributesTransfer $localizedAttributesTransfer
     * @param string[][] $data
     *
     * @return string[][]
     */
    protected function addLocalizedAttributes(
        LocalizedAttributesTransfer $localizedAttributesTransfer,
        array $data
    ) {
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
