<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;

class MatrixGenerator
{
    const SKU_TYPE_SEPARATOR = '-';
    const SKU_VALUE_SEPARATOR = '_';

    public function t()
    {
        $attributeCollection = [
            'size' => [
                '40' => '40',
                '41' => '41',
            ],
            'color' => [
                'blue' => 'Blue',
                'red' => 'Red',
                'white' => 'White',
            ],
            'flavour' => [
                'spicy' => 'Mexican Food',
                'sweet' => 'Cakes'
            ]
        ];

        $attributes = [];
        foreach ($attributeCollection as $attributeType => $attributeValueSet) {
            $typeAttributesValues = [];
            foreach ($attributeValueSet as $name => $value) {
                $typeAttributesValues[] = [$attributeType => $name];
            }

            $attributes[] = $typeAttributesValues;
        }

        $attributesCount = count($attributes);
        $current = array_pad([], $attributesCount, 0);

        $changeIndex = 0;

        function gatherTokens($attributes, $current, $attributesCount)
        {
            echo "<pre>";
            $orderedTokens = [];
            $unorderedTokenCollection = [];

            for ($i=0; $i<$attributesCount; $i++) {
                list($type, $value) = each($attributes[$i][$current[$i]]);
                $unorderedTokenCollection[$type] = $value;
            }

            ksort($unorderedTokenCollection, SORT_STRING | SORT_FLAG_CASE);

            foreach ($unorderedTokenCollection as $type => $value) {
                $orderedTokens[] = [$type => $value];
            }

            $sku = '';
            for ($a=0; $a<count($orderedTokens); $a++) {
                foreach ($orderedTokens[$a] as $type => $value) {
                    $sku .= $type . '-' . $value . '_';
                }
            }

            $sku = rtrim($sku, '_');

            return [
                'tokens' => $orderedTokens,
                'sku' => $sku
            ];
        }

        $result = [];
        while ($changeIndex < $attributesCount) {
            $result[] = gatherTokens($attributes, $current, $attributesCount);
            $changeIndex = 0;

            while ($changeIndex < $attributesCount) {
                $current[$changeIndex]++;

                if ($current[$changeIndex] === count($attributes[$changeIndex])) {
                    $current[$changeIndex] = 0;
                    $changeIndex++;
                } else {
                    break;
                }
            }
        }

        echo "<pre>";
        print_r($result);
        die;
    }

    /**
     * @param array $attributes
     * @param array $current
     * @param int $attributesCount
     *
     * @return array
     */
    protected function gatherTokens(array $attributes, array $current, $attributesCount)
    {
        $tokens = [];
        for ($i=0; $i<$attributesCount; $i++) {
            list($type, $value) = each($attributes[$i][$current[$i]]);
            $tokens[$type] = $value;
        }

        $orderedTokens = $this->sortTokens($tokens);
        $sku = $this->generateSku($orderedTokens);

        return [
            'tokens' => $orderedTokens,
            'sku' => $sku
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
     * @param array $orderedTokens
     *
     * @return string
     */
    protected function generateSku(array $orderedTokens)
    {
        $sku = '';
        for ($a=0; $a<count($orderedTokens); $a++) {
            foreach ($orderedTokens[$a] as $type => $value) {
                $sku .= $type . self::SKU_TYPE_SEPARATOR . $value . self::SKU_VALUE_SEPARATOR;
            }
        }

        return rtrim($sku, self::SKU_VALUE_SEPARATOR);
    }

    /**
     * @param array $attributeCollection
     *
     * @return array
     */
    public function generateTokens(array $attributeCollection)
    {
        $attributes = [];
        foreach ($attributeCollection as $attributeType => $attributeValueSet) {
            $typeAttributesValues = [];
            foreach ($attributeValueSet as $name => $value) {
                $typeAttributesValues[] = [$attributeType => $name];
            }

            $attributes[] = $typeAttributesValues;
        }

        $attributesCount = count($attributes);
        $current = array_pad([], $attributesCount, 0);
        $changeIndex = 0;

        $result = [];
        while ($changeIndex < $attributesCount) {
            $result[] = $this->gatherTokens($attributes, $current, $attributesCount);
            $changeIndex = 0;

            while ($changeIndex < $attributesCount) {
                $current[$changeIndex]++;

                if ($current[$changeIndex] === count($attributes[$changeIndex])) {
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection)
    {

    }

    public function generate222(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection, $currentSku, &$level = 0)
    {
        $productMatrix = [];
        foreach ($attributeCollection as $attributeType => $attributeValueSet) {
            foreach ($attributeValueSet as $name => $value) {
                $concreteSku = $this->generateSku($currentSku, $name);

/*                $productTransfer = (new ProductConcreteTransfer())
                    ->fromArray($productAbstractTransfer->toArray(), true)
                    ->setSku($concreteSku)
                    ->setProductAbstractSku($productAbstractTransfer->getSku())
                    ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());*/

                //$s = $productTransfer->toArray(true);
                //$s['localized_attributes'] = (array) $s['localized_attributes'];
                //unset($s['localized_attributes']);
                $productMatrix[$currentSku][$name] = $value;

                $productAttributeCollection = $attributeCollection;
                unset($productAttributeCollection[$attributeType]);

                $productMatrix += $this->generate($productAbstractTransfer, $productAttributeCollection, $concreteSku);
            }

        }

        $level++;

        return $productMatrix;
    }

}
