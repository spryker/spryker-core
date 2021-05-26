<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;

class ProductConcreteAttributeGuiTableConfigurationProvider implements ProductConcreteAttributeGuiTableConfigurationProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface
     */
    protected $productAttributeGuiTableConfigurationProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProviderInterface $productAttributeGuiTableConfigurationProvider
     */
    public function __construct(ProductAttributeGuiTableConfigurationProviderInterface $productAttributeGuiTableConfigurationProvider)
    {
        $this->productAttributeGuiTableConfigurationProvider = $productAttributeGuiTableConfigurationProvider;
    }

    /**
     * @phpstan-param ArrayObject<string, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param string[] $attributes
     * @param string[] $superAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(array $attributes, array $superAttributes, ArrayObject $localizedAttributeTransfers): GuiTableConfigurationTransfer
    {
        $attributes = $this->filterSuperAttributes($attributes, $superAttributes);

        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $localizedAttributes = $localizedAttributesTransfer->getAttributes();
            $localizedAttributes = $this->filterSuperAttributes($localizedAttributes, $superAttributes);
            $localizedAttributesTransfer->setAttributes($localizedAttributes);
        }

        return $this->productAttributeGuiTableConfigurationProvider->getConfiguration($attributes, $localizedAttributeTransfers);
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
                unset($attributes[$attributeKey]);
            }
        }

        return $attributes;
    }
}
