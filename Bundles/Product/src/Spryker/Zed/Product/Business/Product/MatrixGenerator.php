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

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeCollection
     *
     * @return array
     */
    public function generate(ProductAbstractTransfer $productAbstractTransfer, array $attributeCollection, $currentSku=null)
    {
        if (!$currentSku) {
            $currentSku = $productAbstractTransfer->getSku();
        }

        $productMatrix = [];
        foreach ($attributeCollection as $attributeType => $attributeValueSet) {
            $abstractSku = $this->generateSku($currentSku, $attributeType);

            foreach ($attributeValueSet as $name => $value) {
                $concreteSku = $this->generateSku($abstractSku, $name);

                $productTransfer = (new ProductConcreteTransfer())
                    ->fromArray($productAbstractTransfer->toArray(), true)
                    ->setSku($concreteSku)
                    ->setProductAbstractSku($productAbstractTransfer->getSku())
                    ->setIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

                $s = $productTransfer->toArray(true);
                //$s['localized_attributes'] = (array) $s['localized_attributes'];
                //unset($s['localized_attributes']);
                $productMatrix[] = $s;

                $productAttributeCollection = $attributeCollection;
                unset($productAttributeCollection[$attributeType]);

                $productMatrix = array_merge(
                    $productMatrix,
                    $this->generate($productAbstractTransfer, $productAttributeCollection, $concreteSku)
                );
            }
        }

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
