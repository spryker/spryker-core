<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Reader;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    protected $productUrlManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface
     */
    protected $productConcreteMerger;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface $productUrlManager
     * @param \Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface $productConcreteMerger
     */
    public function __construct(
        ProductConcreteManagerInterface $productConcreteManager,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductUrlManagerInterface $productUrlManager,
        ProductConcreteMergerInterface $productConcreteMerger
    ) {
        $this->productConcreteManager = $productConcreteManager;
        $this->productAbstractManager = $productAbstractManager;
        $this->productUrlManager = $productUrlManager;
        $this->productConcreteMerger = $productConcreteMerger;
    }

    /**
     * @param int $productConcreteId
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function readProductConcreteMergedWithProductAbstractById(int $productConcreteId): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->productConcreteManager->findProductConcreteById($productConcreteId);

        $productAbstractTransfer = $this->productAbstractManager->findProductAbstractById($productConcreteTransfer->getFkProductAbstract());

        $productConcreteTransfer->setUrl($this->productUrlManager->getProductUrl($productAbstractTransfer));

        return $this->productConcreteMerger->mergeProductConcreteWithProductAbstract(
            $productConcreteTransfer,
            $productAbstractTransfer,
        );
    }
}
