<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;

class VariantGenerator implements VariantGeneratorInterface
{

    const TOKENS = 'tokens';
    const SKU = 'sku';

    const SKU_ABSTRACT_SEPARATOR = '-';
    const SKU_TYPE_SEPARATOR = '-';
    const SKU_VALUE_SEPARATOR = '_';

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     */
    public function __construct(ProductToUrlInterface $urlFacade)
    {
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param array $orderedTokenCollection
     *
     * @return string
     */
    protected function generateSkuFromTokens(array $orderedTokenCollection)
    {
        $sku = '';
        for ($a = 0; $a < count($orderedTokenCollection); $a++) {
            foreach ($orderedTokenCollection[$a] as $type => $value) {
                $sku .= $type . self::SKU_TYPE_SEPARATOR . $value . self::SKU_VALUE_SEPARATOR;
            }
        }

        return rtrim($sku, self::SKU_VALUE_SEPARATOR);
    }

    /**
     * @param string $abstractSku
     * @param string $concreteSku
     *
     * @return string
     */
    protected function formatConcreteSku($abstractSku, $concreteSku)
    {
        return $this->urlFacade->slugify(sprintf(
            '%s%s%s',
            $abstractSku,
            self::SKU_ABSTRACT_SEPARATOR,
            $concreteSku
        ));
    }

    /**
     * @param array $attributeCollection
     * @param array $current
     * @param int $attributeCount
     *
     * @return array
     */
    protected function collectTokens(array $attributeCollection, array $current, $attributeCount)
    {
        $tokens = [];
        for ($a = 0; $a < $attributeCount; $a++) {
            list($type, $value) = each($attributeCollection[$a][$current[$a]]);
            $tokens[$type] = $value;
        }

        $orderedTokens = $this->sortTokens($tokens);
        $sku = $this->generateSkuFromTokens($orderedTokens);

        return [
            self::TOKENS => $orderedTokens,
            self::SKU => $sku
        ];
    }

    /**
     * @param array $unorderedTokenCollection
     *
     * @return array
     */
    protected function sortTokens(array $unorderedTokenCollection)
    {
        ksort($unorderedTokenCollection, SORT_STRING | SORT_FLAG_CASE);

        $orderedTokens = [];
        foreach ($unorderedTokenCollection as $type => $value) {
            $orderedTokens[] = [$type => $value];
        }

        return $orderedTokens;
    }

    /**
     * @param array $attributeCollection
     *
     * @return array
     */
    protected function convertAttributesIntoTokens(array $attributeCollection)
    {
        $attributes = [];
        foreach ($attributeCollection as $attributeType => $attributeValueSet) {
            $typeAttributesValues = [];
            foreach ($attributeValueSet as $name => $value) {
                $typeAttributesValues[] = [$attributeType => $value];
            }

            $attributes[] = $typeAttributesValues;
        }

        return $attributes;
    }

    /**
     * @param array $tokenCollection
     *
     * @return array
     */
    protected function convertTokensIntoAttributes(array $tokenCollection)
    {
        $attributes = [];
        for ($a = 0; $a < count($tokenCollection); $a++) {
            foreach ($tokenCollection[$a] as $attributeType => $attributeValue) {
                $attributes[$attributeType] = $attributeValue;
            }
        }

        return $attributes;
    }

    /**
     * @param array $tokenAttributeCollection
     *
     * @return array
     */
    public function generateTokens(array $tokenAttributeCollection)
    {
        $attributeCount = count($tokenAttributeCollection);
        $current = array_pad([], $attributeCount, 0);
        $changeIndex = 0;

        $result = [];
        while ($changeIndex < $attributeCount) {
            $result[] = $this->collectTokens($tokenAttributeCollection, $current, $attributeCount);
            $changeIndex = 0;

            while ($changeIndex < $attributeCount) {
                $current[$changeIndex]++;

                if ($current[$changeIndex] === count($tokenAttributeCollection[$changeIndex])) {
                    $current[$changeIndex] = 0;
                    $changeIndex++;
                } else {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * $attributeCollection = Array
     *  (
     *     [color] => Array
     *      (
     *          [red] => Red
     *          [blue] => Blue
     *      )
     *     [flavour] => Array
     *      (
     *          [sweet] => Cakes
     *      )
     *     [size] => Array
     *      (
     *          [40] => 40
     *          [41] => 41
     *          [42] => 42
     *          )
     *      )
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array|\Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection)
    {
        if (empty($productAbstractTransfer)) {
            return [];
        }

        $tokenCollection = $this->generateTokens(
            $this->convertAttributesIntoTokens($attributeCollection)
        );

        $result = [];
        foreach ($tokenCollection as $token) {
            $sku = $this->formatConcreteSku(
                $productAbstractTransfer->requireSku()->getSku(),
                $token[self::SKU]
            );
            $attributeTokens = $this->convertTokensIntoAttributes($token[self::TOKENS]);

            $result[] = $this->createProductConcreteTransfer($productAbstractTransfer, $sku, $attributeTokens);
        }

        return $result;
    }

    /**
     * @param array $superAttributes
     * @param int $idProductConcrete
     * @param array $variants
     *
     * @return array
     */
    public function generateAttributePermutations(array $superAttributes, $idProductConcrete, array $variants = [])
    {
        if (empty($superAttributes)) {
            $result = [
                ProductConstants::VARIANT_LEAF_NODE_ID => $idProductConcrete //set leaf node to id of concrete product
            ];
        }  else {
            $result = [];

            $index = 0;
            foreach ($superAttributes as $key => $value) {
                $newAttributes = $superAttributes;
                $newVariants = $variants;

                $newVariants[] = array_splice($newAttributes, $index++, 1);

                $recurseResult = $this->generateAttributePermutations($newAttributes, $idProductConcrete, $newVariants);
                if (is_array($recurseResult)) {
                    $recurseResult = array_merge($result, $recurseResult);
                }

                $result[$key . ProductConstants::ATTRIBUTE_MAP_PATH_DELIMITER . $value] = $recurseResult;
            }
        }
        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $concreteSku
     * @param array $attributeTokens
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        $concreteSku,
        array $attributeTokens
    ) {
        return (new ProductConcreteTransfer())
            ->fromArray($productAbstractTransfer->toArray(), true)
            ->setSku($concreteSku)
            ->setAbstractSku($productAbstractTransfer->getSku())
            ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->setAttributes($attributeTokens)
            ->setIsActive(false);
    }

}
