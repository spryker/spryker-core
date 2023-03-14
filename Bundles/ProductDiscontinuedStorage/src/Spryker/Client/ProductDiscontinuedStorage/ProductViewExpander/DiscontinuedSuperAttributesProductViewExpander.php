<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class DiscontinuedSuperAttributesProductViewExpander implements DiscontinuedSuperAttributesProductViewExpanderInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SUPER_ATTRIBUTE_DISCONTINUED = 'product_discontinued.super_attribute_discontinued';

    /**
     * @var string
     */
    protected const PATTERN_DISCONTINUED_ATTRIBUTE_NAME = '%s - %s';

    /**
     * @var string
     */
    protected const PATTERN_ATTRIBUTE_KEY_VALUE_KEY = '%s:%s';

    /**
     * @var string
     */
    protected const ID_PRODUCT_CONCRETE = 'id_product_concrete';

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    protected $productDiscontinuedStorageReader;

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig
     */
    protected ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
     * @param \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig
     */
    public function __construct(
        ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader,
        ProductDiscontinuedStorageToGlossaryStorageClientInterface $glossaryStorageClient,
        ProductDiscontinuedStorageConfig $productDiscontinuedStorageConfig
    ) {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->productDiscontinuedStorageConfig = $productDiscontinuedStorageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandDiscontinuedProductSuperAttributes(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        if (!$productViewTransfer->getAttributeMap()) {
            return $productViewTransfer;
        }
        $superAttributes = $productViewTransfer->getAttributeMapOrFail()->getSuperAttributes();
        $selectedAttributes = $productViewTransfer->getSelectedAttributes();

        if (count($superAttributes) - count($selectedAttributes) > 1) {
            return $productViewTransfer;
        }

        if ($productViewTransfer->getAttributeMapOrFail()->getAttributeVariantMap()) {
            return $this->expandProductAttributeValuesWithDiscontinuedPostfix($productViewTransfer, $localeName);
        }

        $this->prepareProductSuperAttributes($productViewTransfer->getAttributeMapOrFail(), $localeName);

        return $productViewTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only. Use {@link expandProductAttributeValuesWithDiscontinuedPostfix()} instead.
     *
     * @param \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer
     * @param string $localeName
     *
     * @return void
     */
    protected function prepareProductSuperAttributes(AttributeMapStorageTransfer $attributeMapStorageTransfer, string $localeName): void
    {
        $superAttributes = $attributeMapStorageTransfer->getSuperAttributes();
        $attributeVariants = $attributeMapStorageTransfer->getAttributeVariants();

        foreach ($superAttributes as $attributeKey => $attribute) {
            foreach ($attribute as $valueKey => $value) {
                $idProductConcrete = $this->findIdProductConcreteByAttributeValueKey(
                    $this->getAttributeValueKey($attributeKey, $value),
                    $attributeMapStorageTransfer,
                );
                if (!$idProductConcrete) {
                    continue;
                }
                $sku = $this->getSkuByIdProductConcrete($idProductConcrete, $attributeMapStorageTransfer);
                $value = $this->expandAttributeName($value, $sku, $localeName);

                $superAttributes[$attributeKey][$valueKey] = $value;
                $attributeVariants[$this->getAttributeValueKey($attributeKey, $value)][static::ID_PRODUCT_CONCRETE] = $idProductConcrete;
            }
        }
        $attributeMapStorageTransfer->setSuperAttributes($superAttributes);
        $attributeMapStorageTransfer->setAttributeVariants($attributeVariants);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function expandProductAttributeValuesWithDiscontinuedPostfix(
        ProductViewTransfer $productViewTransfer,
        string $localeName
    ): ProductViewTransfer {
        $attributeMapStorageTransfer = $productViewTransfer->getAttributeMapOrFail();
        $selectedAttributes = $productViewTransfer->getSelectedAttributes();

        $superAttributes = $attributeMapStorageTransfer->getSuperAttributes();
        $attributeVariantMap = $attributeMapStorageTransfer->getAttributeVariantMap();

        foreach ($attributeVariantMap as $idProductConcrete => $attributes) {
            $isWholeAttributeVariantSelected = $this->haveEqualAttributesSet($attributes, $selectedAttributes);

            $sku = $this->getSkuByIdProductConcrete($idProductConcrete, $attributeMapStorageTransfer);
            foreach ($attributes as $attributeName => $attributeValue) {
                if (
                    $this->isDiscontinuedPostfixSkippedForAttribute(
                        $superAttributes,
                        $selectedAttributes,
                        $attributeName,
                        $attributeValue,
                    )
                ) {
                    continue;
                }

                $expandedAttributeValue = $this->expandAttributeName($attributeValue, $sku, $localeName);
                if ($attributeValue == $expandedAttributeValue) {
                    continue;
                }
                $attributeVariantMap[$idProductConcrete][$attributeName] = $expandedAttributeValue;

                if ($this->isDiscontinuedPostfixApplicableForSuperAttributes($isWholeAttributeVariantSelected)) {
                    $superAttributes[$attributeName] = $this->expandSuperAttributeValues(
                        $superAttributes[$attributeName],
                        $attributeValue,
                        $expandedAttributeValue,
                    );
                }
            }

            if ($this->isDiscontinuedPostfixApplicableForSuperAttributes($isWholeAttributeVariantSelected)) {
                $selectedAttributes = $attributeVariantMap[$idProductConcrete];
            }
        }

        $attributeMapStorageTransfer
            ->setSuperAttributes($superAttributes)
            ->setAttributeVariantMap($attributeVariantMap);
        $productViewTransfer->setSelectedAttributes($selectedAttributes);

        return $productViewTransfer;
    }

    /**
     * @param bool $isWholeAttributeVariantSelected
     *
     * @return bool
     */
    protected function isDiscontinuedPostfixApplicableForSuperAttributes(bool $isWholeAttributeVariantSelected): bool
    {
        return !$this->productDiscontinuedStorageConfig->isOnlyDiscontinuedVariantAttributesPostfixEnabled()
            || $isWholeAttributeVariantSelected;
    }

    /**
     * @param array<string, list<mixed>> $superAttributes
     * @param array<string, string> $selectedAttributes
     * @param string $attributeName
     * @param string $attributeValue
     *
     * @return bool
     */
    protected function isDiscontinuedPostfixSkippedForAttribute(
        array $superAttributes,
        array $selectedAttributes,
        string $attributeName,
        string $attributeValue
    ): bool {
        $isOnlyDiscontinuedVariantAttributesPostfixEnabled = $this->productDiscontinuedStorageConfig
            ->isOnlyDiscontinuedVariantAttributesPostfixEnabled();

        $unselectedAttributesCount = count($superAttributes) - count($selectedAttributes);
        $isAttributeSelected = (bool)array_intersect_assoc($selectedAttributes, [$attributeName => $attributeValue]);

        return $isOnlyDiscontinuedVariantAttributesPostfixEnabled &&
            $unselectedAttributesCount &&
            $isAttributeSelected;
    }

    /**
     * @param array<string, mixed> $attributes
     * @param array<string, string> $selectedAttributes
     *
     * @return bool
     */
    protected function haveEqualAttributesSet(array $attributes, array $selectedAttributes): bool
    {
        foreach ($attributes as $attributeKey => $attributeValue) {
            if (!array_key_exists($attributeKey, $selectedAttributes) || $selectedAttributes[$attributeKey] !== (string)$attributeValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param list<mixed> $productAttributes
     * @param string $attributeValue
     * @param string $expandedAttributeValue
     *
     * @return list<mixed>
     */
    protected function expandSuperAttributeValues(
        array $productAttributes,
        string $attributeValue,
        string $expandedAttributeValue
    ): array {
        $newSuperAttributes = [];
        foreach ($productAttributes as $productAttributeValue) {
            $newSuperAttributes[] = ((string)$productAttributeValue === $attributeValue) ? $expandedAttributeValue : $productAttributeValue;
        }

        return $newSuperAttributes;
    }

    /**
     * @param string $attributeKey
     * @param string $attributeName
     *
     * @return string
     */
    protected function getAttributeValueKey(string $attributeKey, string $attributeName): string
    {
        return sprintf(
            static::PATTERN_ATTRIBUTE_KEY_VALUE_KEY,
            $attributeKey,
            $attributeName,
        );
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param string $attributeValueKey
     * @param \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer
     *
     * @return int|null
     */
    protected function findIdProductConcreteByAttributeValueKey(string $attributeValueKey, AttributeMapStorageTransfer $attributeMapStorageTransfer): ?int
    {
        return $attributeMapStorageTransfer->getAttributeVariants()[$attributeValueKey][static::ID_PRODUCT_CONCRETE] ?? null;
    }

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer
     *
     * @return string
     */
    protected function getSkuByIdProductConcrete(int $idProductConcrete, AttributeMapStorageTransfer $attributeMapStorageTransfer): string
    {
        return (string)array_search($idProductConcrete, $attributeMapStorageTransfer->getProductConcreteIds());
    }

    /**
     * @param string $value
     * @param string $sku
     * @param string $localeName
     *
     * @return string
     */
    protected function expandAttributeName(string $value, string $sku, string $localeName): string
    {
        if ($this->productDiscontinuedStorageReader->findProductDiscontinuedStorage($sku, $localeName)) {
            $value = sprintf(
                static::PATTERN_DISCONTINUED_ATTRIBUTE_NAME,
                $value,
                $this->glossaryStorageClient->translate(static::GLOSSARY_KEY_SUPER_ATTRIBUTE_DISCONTINUED, $localeName),
            );
        }

        return $value;
    }
}
