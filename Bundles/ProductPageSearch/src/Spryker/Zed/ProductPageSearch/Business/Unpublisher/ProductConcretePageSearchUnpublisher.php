<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Unpublisher;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface;

class ProductConcretePageSearchUnpublisher implements ProductConcretePageSearchUnpublisherInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface
     */
    protected $productConcretePageSearchReader;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface
     */
    protected $productConcretePageSearchWriter;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchReader\ProductConcretePageSearchReaderInterface $productConcretePageSearchReader
     * @param \Spryker\Zed\ProductPageSearch\Business\ProductConcretePageSearchWriter\ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
     */
    public function __construct(
        ProductConcretePageSearchReaderInterface $productConcretePageSearchReader,
        ProductConcretePageSearchWriterInterface $productConcretePageSearchWriter
    ) {
        $this->productConcretePageSearchReader = $productConcretePageSearchReader;
        $this->productConcretePageSearchWriter = $productConcretePageSearchWriter;
    }

    /**
     * @param array $productAbstractStoreMap Keys are product abstract IDs, values are store IDs.
     *
     * @return void
     */
    public function unpublishByAbstractProductsAndStores(array $productAbstractStoreMap): void
    {
        $productConcretePageSearchTransfers = $this->productConcretePageSearchReader->getProductConcretePageSearchTransfersByProductAbstractStoreMap($productAbstractStoreMap);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcretePageSearchTransfers) {
            $this->executeUnpublishTransaction($productConcretePageSearchTransfers);
        });
    }

    /**
     * @param array $productConcretePageSearchTransfers
     *
     * @return void
     */
    protected function executeUnpublishTransaction(array $productConcretePageSearchTransfers): void
    {
        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $this->productConcretePageSearchWriter->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
        }
    }
}
