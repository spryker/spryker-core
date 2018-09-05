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
     * @param string $idProductAbstract
     *
     * @return string
     */
    public function generateProductConcreteSkuIncrement(string $idProductAbstract): string
    {
        $productConcreteTransfers = $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);

        return $this->generateProductConcreteSkuIncrementValue($productConcreteTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return string
     */
    protected function generateProductConcreteSkuIncrementValue(array $productConcreteTransfers): string
    {
        $productConcreteSkuMaxLastId = 1;

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteSkuLastId = $this->getProductConcreteSkuLastPartIncremented($productConcreteTransfer->getSku());

            if ($productConcreteSkuLastId > $productConcreteSkuMaxLastId) {
                $productConcreteSkuMaxLastId = $productConcreteSkuLastId;
            }
        }

        return (string)$productConcreteSkuMaxLastId;
    }

    /**
     * @param string $productConcreteSku
     *
     * @return int
     */
    protected function getProductConcreteSkuLastPartIncremented(string $productConcreteSku): int
    {
        if (strpos($productConcreteSku, SkuGenerator::SKU_ABSTRACT_SEPARATOR) !== false) {
            $productConcreteSku = substr($productConcreteSku, strrpos($productConcreteSku, SkuGenerator::SKU_ABSTRACT_SEPARATOR) + 1);
        }

        if (!is_numeric($productConcreteSku)) {
            return 0;
        }

        return (int)$productConcreteSku + 1;
    }
}
