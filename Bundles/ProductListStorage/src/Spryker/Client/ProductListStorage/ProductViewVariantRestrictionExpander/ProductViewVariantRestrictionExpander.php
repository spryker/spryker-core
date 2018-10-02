<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage\ProductViewVariantRestrictionExpander;

use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface;

class ProductViewVariantRestrictionExpander implements ProductViewVariantRestrictionExpanderInterface
{
    protected const PATTERN_ATTRIBUTE_KEY_VALUE_KEY = '%s:%s';
    protected const ID_PRODUCT_CONCRETE = 'id_product_concrete';

    /**
     * @var \Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface
     */
    protected $productConcreteRestrictionReader;

    /**
     * @param \Spryker\Client\ProductListStorage\ProductConcreteRestriction\ProductConcreteRestrictionReaderInterface $productConcreteRestrictionReader
     */
    public function __construct(
        ProductConcreteRestrictionReaderInterface $productConcreteRestrictionReader
    ) {
        $this->productConcreteRestrictionReader = $productConcreteRestrictionReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductVariantData(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $attributeMapStorageTransfer = $productViewTransfer->getAttributeMap();
        if (!$attributeMapStorageTransfer) {
            return $productViewTransfer;
        }

        $this->filterRestrictedConcreteProducts($productViewTransfer->getAttributeMap());

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AttributeMapStorageTransfer $attributeMapStorageTransfer
     *
     * @return void
     */
    protected function filterRestrictedConcreteProducts(AttributeMapStorageTransfer $attributeMapStorageTransfer): void
    {
        $superAttributes = $attributeMapStorageTransfer->getSuperAttributes();
        $attributeVariants = $attributeMapStorageTransfer->getAttributeVariants();
        $productConcreteIds = $attributeMapStorageTransfer->getProductConcreteIds();

        $notRestrictedAttributes = [];
        $notRestrictedAttributeVariants = [];
        $notRestrictedProductConcreteIds = [];
        foreach ($superAttributes as $attributeKey => $attribute) {
            foreach ($attribute as $valueKey => $value) {
                $idProductConcrete = $this->findIdProductConcreteByAttributeValueKey(
                    $this->getAttributeValueKey($attributeKey, $value),
                    $attributeVariants
                );

                if (!$idProductConcrete || $this->productConcreteRestrictionReader->isProductConcreteRestricted($idProductConcrete)) {
                    continue;
                }

                $notRestrictedAttributes[$attributeKey][] = $value;
                $notRestrictedAttributeVariants[$this->getAttributeValueKey($attributeKey, $value)][static::ID_PRODUCT_CONCRETE] = $idProductConcrete;
                $notRestrictedProductConcreteIds[$this->getSkuByIdProductConcrete($idProductConcrete, $productConcreteIds)] = $idProductConcrete;
            }
        }
        $attributeMapStorageTransfer->setSuperAttributes($notRestrictedAttributes);
        $attributeMapStorageTransfer->setAttributeVariants($notRestrictedAttributeVariants);
        $attributeMapStorageTransfer->setProductConcreteIds($notRestrictedProductConcreteIds);
    }

    /**
     * @param int $idProductConcrete
     * @param array $productConcreteIds
     *
     * @return string
     */
    protected function getSkuByIdProductConcrete(int $idProductConcrete, array $productConcreteIds): string
    {
        return (string)array_search($idProductConcrete, $productConcreteIds);
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
     * @param array $attributeVariants
     *
     * @return int|null
     */
    protected function findIdProductConcreteByAttributeValueKey(string $attributeValueKey, array $attributeVariants): ?int
    {
        return $attributeVariants[$attributeValueKey][static::ID_PRODUCT_CONCRETE] ?? null;
    }
}
