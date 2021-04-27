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
    public const FIELD_NAME = 'name';
    public const FIELD_SKU = 'sku';
    public const FIELD_ATTRIBUTE = 'attribute';
    public const FIELD_SUPER_ATTRIBUTES = 'superAttributes';
    public const FIELD_KEY = 'key';
    public const FIELD_VALUE = 'value';
    public const FIELD_TITLE = 'title';

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
     * @param array $concreteProducts
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function mapRequestDataToProductConcreteTransfer(array $concreteProducts): array
    {
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        $concreteProductTransfers = [];
        foreach ($concreteProducts as $concreteProduct) {
            $concreteProductTransfer = (new ProductConcreteTransfer())
                ->setSku($concreteProduct[static::FIELD_SKU])
                ->setName($concreteProduct[static::FIELD_NAME]);

            $attributes = $this->reformatSuperAttributes($concreteProductTransfer);
            $productManagementAttributeTransfers = $this->getProductManagementAttributes($attributes);

            foreach ($localeTransfers as $localeTransfer) {
                $localizedAttributes = $this->extractLocalizedAttributes(
                    $productManagementAttributeTransfers->getArrayCopy(),
                    $attributes,
                    $localeTransfer
                );

                $concreteProductTransfer->addLocalizedAttributes(
                    (new LocalizedAttributesTransfer())
                        ->setName($concreteProduct[static::FIELD_NAME])
                        ->setLocale($localeTransfer)
                        ->setAttributes($localizedAttributes)
                );
            }

            $concreteProductTransfers[] = $concreteProductTransfer;
        }

        return $concreteProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $concreteProductTransfer
     *
     * @return string[]
     */
    protected function reformatSuperAttributes(ProductConcreteTransfer $concreteProductTransfer): array
    {
        $attributes = [];
        foreach ($concreteProductTransfer as $superAttribute) {
            $attributeKey = $superAttribute[static::FIELD_VALUE];
            $attributeValue = $superAttribute[static::FIELD_ATTRIBUTE][static::FIELD_VALUE];
            $attributes[$attributeKey] = $attributeValue;
        }

        return $attributes;
    }

    /**
     * @param array $attributes
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
                if ($attributeValueTranslationTransfer->getLocaleName() === $localeTransfer->getLocaleName()) {
                    $localizedAttributes[$attributeKey] = $attributeValueTranslationTransfer->getTranslation();
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
            foreach ($productManagementAttributeTransfer->getValues() as $productManagementAttributeValueTransfer) {
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
