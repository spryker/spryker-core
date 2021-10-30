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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
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
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param array<string> $attributes
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    public function extractSuperAttributes(
        array $attributes,
        ArrayObject $localizedAttributeTransfers,
        LocaleTransfer $localeTransfer
    ): array {
        $localizedAttributesTransfer = $this->extractLocalizedAttributes($localizedAttributeTransfers, $localeTransfer);
        $localizedAttributes = $localizedAttributesTransfer ? $localizedAttributesTransfer->getAttributes() : [];
        $attributes = array_merge($attributes, $localizedAttributes);
        $productManagementAttributeTransfers = $this->productAttributeFacade->getProductManagementAttributes(
            (new ProductManagementAttributeFilterTransfer())->setKeys(array_keys($attributes)),
        )->getProductManagementAttributes();

        $superAttributes = [];
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $attributeKey = $productManagementAttributeTransfer->getKeyOrFail();

            if ($productManagementAttributeTransfer->getIsSuperOrFail() && isset($attributes[$attributeKey])) {
                $superAttributes[$attributeKey] = $attributes[$attributeKey];
            }
        }

        return $superAttributes;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     *
     * @param array<string> $attributes
     * @param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributeTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
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
