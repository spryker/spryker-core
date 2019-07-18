<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Sku;

use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;

class SkuIncrementGenerator implements SkuIncrementGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     */
    public function __construct(ProductConcreteManagerInterface $productConcreteManager)
    {
        $this->productConcreteManager = $productConcreteManager;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    public function generateProductConcreteSkuIncrement(int $idProductAbstract): string
    {
        $productConcreteTransfers = $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);

        return $this->generateProductConcreteSkuIncrementalValue($productConcreteTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return string
     */
    protected function generateProductConcreteSkuIncrementalValue(array $productConcreteTransfers): string
    {
        $productConcreteSkuMaxLastIncrementalValue = 1;

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteSkuLastValue = $this->getProductConcreteSkuLastPartIncremented($productConcreteTransfer->getSku());

            if ($productConcreteSkuLastValue > $productConcreteSkuMaxLastIncrementalValue) {
                $productConcreteSkuMaxLastIncrementalValue = $productConcreteSkuLastValue;
            }
        }

        return (string)$productConcreteSkuMaxLastIncrementalValue;
    }

    /**
     * @param string $productConcreteSku
     *
     * @return int
     */
    protected function getProductConcreteSkuLastPartIncremented(string $productConcreteSku): int
    {
        if (mb_strpos($productConcreteSku, SkuGenerator::SKU_ABSTRACT_SEPARATOR) === false) {
            return 0;
        }

        $productConcreteSku = mb_substr($productConcreteSku, mb_strpos($productConcreteSku, SkuGenerator::SKU_ABSTRACT_SEPARATOR) + 1);

        if (!is_numeric($productConcreteSku) || (int)$productConcreteSku < 0) {
            return 0;
        }

        return (int)$productConcreteSku + 1;
    }
}
