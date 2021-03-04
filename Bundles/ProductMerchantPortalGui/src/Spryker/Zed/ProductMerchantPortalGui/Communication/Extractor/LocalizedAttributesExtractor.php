<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Extractor;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;

class LocalizedAttributesExtractor implements LocalizedAttributesExtractorInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct(ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer|null
     */
    public function extractLocalizedAttributes(
        ArrayObject $localizedAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): ?LocalizedAttributesTransfer {
        foreach ($localizedAttributeTransfers as $localizedAttributesTransfer) {
            $localeFromLocalizedAttributes = $localizedAttributesTransfer->getLocale();
            if (!$localeFromLocalizedAttributes) {
                continue;
            }

            if ($localeFromLocalizedAttributes->getIdLocale() === $localeTransfer->getIdLocale()) {
                return $localizedAttributesTransfer;
            }
        }

        return null;
    }

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
    ): array {
        $attributes = array_merge(
            $attributes,
            $this->extractLocalizedAttributes($localizedAttributeTransfers, $localeTransfer)->getAttributes()
        );
        $productManagementAttributeTransfers = $this->productAttributeFacade->getProductManagementAttributes(
            (new ProductManagementAttributeFilterTransfer())->setKeys(array_keys($attributes))
        )->getProductManagementAttributes();

        $superAttributes = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            if ($productManagementAttributeTransfer->getIsSuper()) {
                $superAttributes[$productManagementAttributeTransfer->getKey()] = $attributes[$productManagementAttributeTransfer->getKey()];
            }
        }

        return $superAttributes;
    }

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
    ): array {
        $superAttributes = $this->extractSuperAttributes($attributes, $localizedAttributeTransfers, $localeTransfer);
        $combinedSuperAttributeNames = [];

        foreach ($superAttributes as $attributeKey => $attributeValue) {
            $combinedSuperAttributeNames[$attributeKey] = ucfirst(sprintf('%s: %s', $attributeKey, $attributeValue));
        }

        return $combinedSuperAttributeNames;
    }
}
