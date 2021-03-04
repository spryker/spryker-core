<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider;

use ArrayObject;

class ProductConcreteAttributeTableDataProvider implements ProductConcreteAttributeTableDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface
     */
    protected $productAttributeTableDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\DataProvider\ProductAttributeTableDataProviderInterface $productAttributeTableDataProvider
     */
    public function __construct(ProductAttributeTableDataProviderInterface $productAttributeTableDataProvider)
    {
        $this->productAttributeTableDataProvider = $productAttributeTableDataProvider;
    }

    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param string[] $attributes
     * @param string[] $superAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     *
     * @return string[][]
     */
    public function getData(array $attributes, array $superAttributes, ArrayObject $localizedAttributeTransfers): array
    {
        $attributes = $this->filterSuperAttributes($attributes, $superAttributes);

        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $localizedAttributes = $localizedAttributesTransfer->getAttributes();
            $localizedAttributes = $this->filterSuperAttributes($localizedAttributes, $superAttributes);
            $localizedAttributesTransfer->setAttributes($localizedAttributes);
        }

        return $this->productAttributeTableDataProvider->getData($attributes, $localizedAttributeTransfers);
    }

    /**
     * @param string[] $attributes
     * @param string[] $superAttributes
     *
     * @return string[]
     */
    protected function filterSuperAttributes(array $attributes, array $superAttributes): array
    {
        foreach ($attributes as $attributeKey => $attribute) {
            if (in_array($attributeKey, $superAttributes)) {
                unset($attribute[$attributeKey]);
            }
        }

        return $attributes;
    }
}
