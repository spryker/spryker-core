<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider;

class ProductAbstractMapper implements ProductAbstractMapperInterface
{
    /**
     * @param array $attributesInitialData
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapAttributesDataToProductAbstractTransfer(
        array $attributesInitialData,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $attributes = $productAbstractTransfer->getAttributes();

        foreach ($attributesInitialData[GuiTableEditableInitialDataTransfer::DATA] as $attribute) {
            $newAttributeName = $attribute[ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME];
            $defaultAttributeValue = $attribute[ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT];

            $attributes[$newAttributeName] = $defaultAttributeValue;

            $productAbstractTransfer = $this->mapLocalizedAttributes($attribute, $productAbstractTransfer);
        }

        $productAbstractTransfer->setAttributes($attributes);

        return $productAbstractTransfer;
    }

    /**
     * @param array $newAttribute
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function mapLocalizedAttributes(array $newAttribute, ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $newAttributeName = $newAttribute[ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME] ?? '';

        if (empty($newAttributeName)) {
            return $productAbstractTransfer;
        }

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $localeName = $localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail();

            if (isset($newAttribute[$localeName]) && !empty($newAttribute[$localeName])) {
                $attrs = $localizedAttribute->getAttributes();
                $attrs[$newAttributeName] = $newAttribute[$localeName];
                $localizedAttribute->setAttributes($attrs);
            }
        }

        return $productAbstractTransfer;
    }
}
