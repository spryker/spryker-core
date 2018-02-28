<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Stock;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleStockHandler implements ProductBundleStockHandlerInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriterInterface
     */
    protected $productBundleStockWriter;

    /**
     * @var array
     */
    protected static $bundledItemEntityCache = [];

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Stock\ProductBundleStockWriterInterface $productBundleStockWriter
     */
    public function __construct(
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleStockWriterInterface $productBundleStockWriter
    ) {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->productBundleStockWriter = $productBundleStockWriter;
    }

    /**
     * @param string $bundledProductSku
     *
     * @return void
     */
    public function updateAffectedBundlesStock(string $bundledProductSku): void
    {
        $bundleProducts = $this->getBundlesUsingProductBySku($bundledProductSku);

        foreach ($bundleProducts as $productBundleEntity) {
            $productBundleTransfer = new ProductConcreteTransfer();
            $productBundleTransfer->setIdProductConcrete($productBundleEntity->getFkProduct());
            $productBundleTransfer->setSku($productBundleEntity->getSpyProductRelatedByFkProduct()->getSku());

            $this->productBundleStockWriter
                ->updateStock($productBundleTransfer);
        }
    }

    /**
     * @param string $bundledProductSku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\ActiveRecord\ActiveRecordInterface[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getBundlesUsingProductBySku($bundledProductSku)
    {
        $isInCache = isset(static::$bundledItemEntityCache[$bundledProductSku])
            && count(static::$bundledItemEntityCache[$bundledProductSku]) > 0;

        if (!$isInCache) {
            static::$bundledItemEntityCache[$bundledProductSku] = $this->productBundleQueryContainer
                ->queryBundledProductBySku($bundledProductSku)
                ->find();
        }

        return static::$bundledItemEntityCache[$bundledProductSku];
    }
}
