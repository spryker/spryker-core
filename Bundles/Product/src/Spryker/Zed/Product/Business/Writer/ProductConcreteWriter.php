<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Writer;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Persistence\ProductEntityManagerInterface;

class ProductConcreteWriter implements ProductConcreteWriterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductEntityManagerInterface
     */
    protected $productEntityManager;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface
     */
    protected $productConcreteAssertion;

    /**
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\Persistence\ProductEntityManagerInterface $productEntityManager
     * @param \Spryker\Zed\Product\Business\Product\Assertion\ProductConcreteAssertionInterface $productConcreteAssertion
     */
    public function __construct(
        ProductConcreteManagerInterface $productConcreteManager,
        ProductEntityManagerInterface $productEntityManager,
        ProductConcreteAssertionInterface $productConcreteAssertion
    ) {
        $this->productConcreteManager = $productConcreteManager;
        $this->productEntityManager = $productEntityManager;
        $this->productConcreteAssertion = $productConcreteAssertion;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    public function createProductConcreteCollection(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        $this->getTransactionHandler()->handleTransaction(function () use ($productConcreteCollectionTransfer): void {
            $this->executeCreateProductConcreteCollectionTransaction($productConcreteCollectionTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     *
     * @return void
     */
    protected function executeCreateProductConcreteCollectionTransaction(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
    ): void {
        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $this->productConcreteAssertion->assertSkuIsUnique($productConcreteTransfer->getSku());
            $this->productConcreteManager->notifyBeforeCreateObservers($productConcreteTransfer);
        }

        $productConcreteCollectionTransfer = $this->productEntityManager
            ->createProductConcreteCollection($productConcreteCollectionTransfer);
        $this->productEntityManager->createProductConcreteCollectionLocalizedAttributes($productConcreteCollectionTransfer);

        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $this->productConcreteManager->notifyAfterCreateObservers($productConcreteTransfer);
        }
    }
}
