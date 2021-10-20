<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Expander;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;

class ProductConcreteLocalizedAttributesExpander implements ProductConcreteLocalizedAttributesExpanderInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_PRODUCT_NAME = '';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct(
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
    ) {
        $this->localeFacade = $localeFacade;
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandLocalizedAttributes(array $productConcreteTransfers): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $currentLocale = $this->localeFacade->getCurrentLocale()->getLocaleNameOrFail();

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $attributes = $productConcreteTransfer->getAttributes();
            $productManagementAttributeTransfers = $this->getProductManagementAttributes($attributes);

            foreach ($localeTransfers as $localeTransfer) {
                $name = $localeTransfer->getLocaleNameOrFail() === $currentLocale
                    ? $productConcreteTransfer->getName()
                    : static::DEFAULT_PRODUCT_NAME;

                $localizedAttributes = $this->extractLocalizedAttributes(
                    $productManagementAttributeTransfers->getArrayCopy(),
                    $attributes,
                    $localeTransfer,
                );

                $productConcreteTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())
                        ->setName($name)
                        ->setLocale($localeTransfer)
                        ->setAttributes($localizedAttributes),
                );
            }
        }

        return $productConcreteTransfers;
    }

    /**
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     *
     * @param array<mixed> $attributes
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    protected function getProductManagementAttributes(array $attributes): ArrayObject
    {
        $productManagementAttributeFilterTransfer = new ProductManagementAttributeFilterTransfer();
        $productManagementAttributeFilterTransfer->setKeys(array_keys($attributes));

        return $this->productAttributeFacade
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer)
            ->getProductManagementAttributes();
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     * @param array<string> $attributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<string>
     */
    protected function extractLocalizedAttributes(
        array $productManagementAttributeTransfers,
        array $attributes,
        LocaleTransfer $localeTransfer
    ): array {
        $localizedAttributes = [];

        foreach ($attributes as $attributeKey => $attributeValue) {
            $productManagementAttributeValueTransfer = $this->extractProductManagementAttributeValueTransfer(
                $attributeKey,
                $attributeValue,
                $productManagementAttributeTransfers,
            );

            if (!$productManagementAttributeValueTransfer) {
                continue;
            }

            foreach ($productManagementAttributeValueTransfer->getLocalizedValues() as $attributeValueTranslationTransfer) {
                if ($attributeValueTranslationTransfer->getLocaleNameOrFail() === $localeTransfer->getLocaleNameOrFail()) {
                    $localizedAttributes[$attributeKey] = $attributeValueTranslationTransfer->getTranslationOrFail();
                }
            }
        }

        return $localizedAttributes;
    }

    /**
     * @param string $attributeKey
     * @param string $attributeValue
     * @param array<\Generated\Shared\Transfer\ProductManagementAttributeTransfer> $productManagementAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer|null
     */
    protected function extractProductManagementAttributeValueTransfer(
        string $attributeKey,
        string $attributeValue,
        array $productManagementAttributeTransfers
    ): ?ProductManagementAttributeValueTransfer {
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $productManagementAttributeValueTransfers = $productManagementAttributeTransfer->getValues();
            foreach ($productManagementAttributeValueTransfers as $productManagementAttributeValueTransfer) {
                if (
                    $attributeKey === $productManagementAttributeTransfer->getKey()
                    && $attributeValue === $productManagementAttributeValueTransfer->getValue()
                ) {
                    return $productManagementAttributeValueTransfer;
                }
            }
        }

        return null;
    }
}
