<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface;
use Spryker\Zed\Product\Persistence\ProductRepositoryInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected ProductConcreteManagerInterface $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface
     */
    protected ProductAbstractManagerInterface $productAbstractManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface
     */
    protected ProductUrlManagerInterface $productUrlManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface
     */
    protected ProductConcreteMergerInterface $productConcreteMerger;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface>
     */
    protected array $productConcreteExpanderPlugins;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface $productAbstractManager
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface $productUrlManager
     * @param \Spryker\Zed\Product\Business\Product\Merger\ProductConcreteMergerInterface $productConcreteMerger
     * @param \Spryker\Zed\Product\Persistence\ProductRepositoryInterface $productRepository
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface> $productConcreteExpanderPlugins
     */
    public function __construct(
        ProductConcreteManagerInterface $productConcreteManager,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductUrlManagerInterface $productUrlManager,
        ProductConcreteMergerInterface $productConcreteMerger,
        ProductRepositoryInterface $productRepository,
        array $productConcreteExpanderPlugins = []
    ) {
        $this->productConcreteManager = $productConcreteManager;
        $this->productAbstractManager = $productAbstractManager;
        $this->productUrlManager = $productUrlManager;
        $this->productConcreteMerger = $productConcreteMerger;
        $this->productRepository = $productRepository;
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
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

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        ProductConcreteCriteriaTransfer $productConcreteCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        $productConcreteCollectionTransfer = $this->productRepository->getProductConcreteCollection($productConcreteCriteriaTransfer);
        $expandedProductConcreteTransfers = $this->executeProductConcreteExpanderPlugins((array)$productConcreteCollectionTransfer->getProducts());

        return $productConcreteCollectionTransfer->setProducts(new ArrayObject($expandedProductConcreteTransfers));
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function executeProductConcreteExpanderPlugins(array $productConcreteTransfers): array
    {
        foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
            $productConcreteTransfers = $productConcreteExpanderPlugin->expand($productConcreteTransfers);
        }

        return $productConcreteTransfers;
    }
}
