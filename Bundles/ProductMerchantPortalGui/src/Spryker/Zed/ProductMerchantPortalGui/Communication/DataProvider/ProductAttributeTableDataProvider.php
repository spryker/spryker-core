<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider;

use ArrayObject;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;

class ProductAttributeTableDataProvider implements ProductAttributeTableDataProviderInterface
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
            foreach ($localizedAttributesTransfer->getAttributes() as $attributeName => $value) {
                if (!isset($data[$attributeName])) {
                    $data[$attributeName] = [
                        ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME => $attributeName,
                    ];
                }

                $data[$attributeName][$localizedAttributesTransfer->getLocaleOrFail()->getLocaleName()] = $value;
            }
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
}
