<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductViewExpander;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class DiscontinuedOptionsProductViewExpander implements DiscontinuedOptionsProductViewExpanderInterface
{
    protected const GLOSSARY_KEY_SUPER_ATTRIBUTE_DISCONTINUED = 'product_discontinued.super_attribute_discontinued';
    protected const PATTERN_DISCONTINUED_ATTRIBUTE_NAME = '%s - %s';
    protected const PATTERN_ATTRIBUTE_KEY_VALUE_KEY = '%s:%s';
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
     * @param \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
     * @param \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader,
        ProductDiscontinuedStorageToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandDiscontinuedProductOptions(ProductViewTransfer $productViewTransfer, string $localeName): ProductViewTransfer
    {
        if (!$productViewTransfer->getAttributeMap()) {
            return $productViewTransfer;
        }
        $superAttributes = $productViewTransfer->getAttributeMap()->getSuperAttributes();
        $selectedAttributes = $productViewTransfer->getSelectedAttributes();
        if (count($superAttributes) - count($selectedAttributes) > 1) {
            return $productViewTransfer;
        }

        $this->prepareProductSuperAttributes($productViewTransfer->getAttributeMap(), $localeName);

        return $productViewTransfer;
    }

    /**
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
                $idProductConcrete = $this->getIdProductConcreteByAttributeValueKey(
                    $this->getAttributeValueKey($attributeKey, $value),
                    $attributeMapStorageTransfer
                );
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
            $attributeName
        );
    }

    /**
     * @param string $attributeValueKey
     * @param \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer
     *
     * @return int
     */
    protected function getIdProductConcreteByAttributeValueKey(string $attributeValueKey, AttributeMapStorageTransfer $attributeMapStorageTransfer): int
    {
        return $attributeMapStorageTransfer->getAttributeVariants()[$attributeValueKey][static::ID_PRODUCT_CONCRETE];
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
                $this->glossaryStorageClient->translate(static::GLOSSARY_KEY_SUPER_ATTRIBUTE_DISCONTINUED, $localeName)
            );
        }

        return $value;
    }
}
