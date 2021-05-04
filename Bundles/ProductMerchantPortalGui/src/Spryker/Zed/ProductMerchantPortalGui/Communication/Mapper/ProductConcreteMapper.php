<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface;

class ProductConcreteMapper implements ProductConcreteMapperInterface
{
    protected const FIELD_NAME = 'name';
    protected const FIELD_SKU = 'sku';
    protected const FIELD_ATTRIBUTE = 'attribute';
    protected const FIELD_SUPER_ATTRIBUTES = 'superAttributes';
    protected const FIELD_KEY = 'key';
    protected const FIELD_VALUE = 'value';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface
     */
    protected $localeDataProvider;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\DataProvider\LocaleDataProviderInterface $localeDataProvider
     */
    public function __construct(
        ProductMerchantPortalGuiToLocaleFacadeInterface $localeFacade,
        ProductMerchantPortalGuiToProductAttributeFacadeInterface $productAttributeFacade,
        LocaleDataProviderInterface $localeDataProvider
    ) {
        $this->localeFacade = $localeFacade;
        $this->productAttributeFacade = $productAttributeFacade;
        $this->localeDataProvider = $localeDataProvider;
    }

    /**
     * @param mixed[] $concreteProducts
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function mapRequestDataToProductConcreteTransfers(array $concreteProducts): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $defaultStoreDefaultLocale = $this->localeDataProvider->findDefaultStoreDefaultLocale();

        $productConcreteTransfers = [];
        foreach ($concreteProducts as $concreteProduct) {
            $attributes = $this->reformatSuperAttributes($concreteProduct);

            $concreteProductTransfer = (new ProductConcreteTransfer())
                ->setSku($concreteProduct[static::FIELD_SKU])
                ->setName($concreteProduct[static::FIELD_NAME])
                ->setIsActive(false)
                ->setAttributes($attributes);

            $productManagementAttributeTransfers = $this->getProductManagementAttributes($attributes);

            foreach ($localeTransfers as $localeTransfer) {
                $localizedAttributes = $this->extractLocalizedAttributes(
                    $productManagementAttributeTransfers->getArrayCopy(),
                    $attributes,
                    $localeTransfer
                );
                $productConcreteLocalizedName = $localeTransfer->getLocaleNameOrFail() === $defaultStoreDefaultLocale
                    ? $concreteProduct[static::FIELD_NAME]
                    : '';

                $concreteProductTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())
                        ->setName($productConcreteLocalizedName)
                        ->setLocale($localeTransfer)
                        ->setAttributes($localizedAttributes)
                );
            }

            $productConcreteTransfers[] = $concreteProductTransfer;
        }

        return $productConcreteTransfers;
    }

    /**
     * @param mixed[] $concreteProduct
     *
     * @return string[]
     */
    protected function reformatSuperAttributes(array $concreteProduct): array
    {
        $attributes = [];

        if (!isset($concreteProduct[static::FIELD_SUPER_ATTRIBUTES])) {
            return $attributes;
        }

        foreach ($concreteProduct[static::FIELD_SUPER_ATTRIBUTES] as $superAttribute) {
            $attributeKey = $superAttribute[static::FIELD_VALUE];
            $attributeValue = $superAttribute[static::FIELD_ATTRIBUTE][static::FIELD_VALUE];
            $attributes[$attributeKey] = $attributeValue;
        }

        return $attributes;
    }

    /**
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     *
     * @param mixed[] $attributes
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     * @param string[] $attributes
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[]
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
                $productManagementAttributeTransfers
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
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer[] $productManagementAttributeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer|null
     */
    protected function extractProductManagementAttributeValueTransfer(
        string $attributeKey,
        string $attributeValue,
        array $productManagementAttributeTransfers
    ): ?ProductManagementAttributeValueTransfer {
        foreach ($productManagementAttributeTransfers as $productManagementAttributeTransfer) {
            $attributeValues = $productManagementAttributeTransfer->getValues();
            foreach ($attributeValues as $productManagementAttributeValueTransfer) {
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
