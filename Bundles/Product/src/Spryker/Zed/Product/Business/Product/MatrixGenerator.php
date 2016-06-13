<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class MatrixGenerator
{

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

        $attributes = [
            ['red', 'blue', 'black'],
            ['big', 'small', 'tiny'],
            ['backed', 'raw']
        ];

        dump($attributes);

        $attributes = [];
        $attributesMeta = [];
        foreach ($attributeCollection as $attributeType => $attributeValueSet) {
            $typeAttributesValues = [];
            $typeAttributesMeta = [];
            foreach ($attributeValueSet as $name => $value) {
                $typeAttributesValues[] = $attributeType . '-' .$name;
                $typeAttributesMeta[$name] = $value;
            }

            $attributes[] = $typeAttributesValues;
            $attributesMeta[] = [
                $attributeType => $typeAttributesMeta
            ];
        }

        dump($attributes, $attributesMeta);

        $attributesCount = count($attributes);
        $current = array_pad([], $attributesCount, 0);

        $changeIndex = 0;

        function gatherTokens($attributes, $current, $attributesCount, $attributesMeta) {
            $result = [];
            $resultMeta = [];
            for ($i=0; $i<$attributesCount; $i++) {
                $result['tokens'][] = $attributes[$i][$current[$i]];
                $result['meta'][] = $attributesMeta[$i];
            }

            return $result;
        }

        $result = [];
        while ($changeIndex < $attributesCount) {
            $result[] = gatherTokens($attributes, $current, $attributesCount, $attributesMeta);
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
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection, $currentSku, &$level=0)
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

    /**
     * @param string $sku
     * @param string $attributeName
     * @param string $attributeValue
     *
     * @return string
     */
    protected function generateSku($sku, $attributeName, $attributeValue = null)
    {
        $format = '%s-%s';
        $attributeName = $this->slugify($attributeName);
        $attributeValue = $this->slugify($attributeValue);

        if ($attributeValue !== null) {
            $format = '%s-%s-%s';
        }

        return sprintf(
            $format,
            $sku,
            $attributeName,
            $attributeValue
        );
    }

    /**
     * @param string $value
     *
     * @return string
     */
    protected function slugify($value)
    {
        return $value;
        if (trim($value) === '') {
            return $value;
        }

        if (function_exists('iconv')) {
            $value = iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        }

        $value = trim($value);
        $value = preg_replace("/[^a-zA-Z0-9 _ -]/", "", $value);
        $value = mb_strtolower($value);
        $value = str_replace(' ', '', $value);

        return $value;
    }

}
