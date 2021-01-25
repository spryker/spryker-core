<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Variant;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;

class VariantGenerator implements VariantGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface
     */
    protected $skuGenerator;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface $skuGenerator
     */
    public function __construct(
        ProductToUrlInterface $urlFacade,
        SkuGeneratorInterface $skuGenerator
    ) {
        $this->urlFacade = $urlFacade;
        $this->skuGenerator = $skuGenerator;
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
            $type = current(array_keys($attributeCollection[$a][$current[$a]]));
            $value = $attributeCollection[$a][$current[$a]][$type];
            $tokens[$type] = $value;
        }

        return $this->sortTokens($tokens);
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
        $numberOfTokens = count($tokenCollection);
        for ($a = 0; $a < $numberOfTokens; $a++) {
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

                if ($current[$changeIndex] !== count($tokenAttributeCollection[$changeIndex])) {
                    break;
                }

                $current[$changeIndex] = 0;
                $changeIndex++;
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
     *     [flavor] => Array
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
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection)
    {
        $tokenCollection = $this->generateTokens(
            $this->convertAttributesIntoTokens($attributeCollection)
        );

        $result = [];
        foreach ($tokenCollection as $token) {
            $attributeTokens = $this->convertTokensIntoAttributes($token);

            $productConcreteTransfer = $this->createProductConcreteTransfer($productAbstractTransfer, $attributeTokens);

            $productConcreteTransfer->setSku(
                $this->skuGenerator->generateProductConcreteSku(
                    $productAbstractTransfer,
                    $productConcreteTransfer
                )
            );
            $result[] = $productConcreteTransfer;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeTokens
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        array $attributeTokens
    ) {
        return (new ProductConcreteTransfer())
            ->fromArray($productAbstractTransfer->modifiedToArray(), true)
            ->setAbstractSku($productAbstractTransfer->getSku())
            ->setFkProductAbstract($productAbstractTransfer->getIdProductAbstract())
            ->setAttributes($attributeTokens)
            ->setIsActive(false);
    }
}
