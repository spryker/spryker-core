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
     * @param array $storesPerAbstractProducts
     *
     * @return void
     */
    public function unpublishByAbstractProductsAndStores(array $storesPerAbstractProducts): void
    {
        $productConcretePageSearchTransfersGroupedByStoreAndLocale = $this->productConcretePageSearchReader
            ->getProductConcretePageSearchTransfersByAbstractProductsAndStores($storesPerAbstractProducts);

        $this->getTransactionHandler()->handleTransaction(function () use ($productConcretePageSearchTransfersGroupedByStoreAndLocale) {
            $this->executeUnpublishTransaction($productConcretePageSearchTransfersGroupedByStoreAndLocale);
        });
    }

    /**
     * @param array $productConcretePageSearchTransfersGroupedByStoreAndLocale
     *
     * @return void
     */
    protected function executeUnpublishTransaction(array $productConcretePageSearchTransfersGroupedByStoreAndLocale): void
    {
        foreach ($productConcretePageSearchTransfersGroupedByStoreAndLocale as $productConcretePageSearchTransfersStores) {
            foreach ($productConcretePageSearchTransfersStores as $productConcretePageSearchTransfersLocales) {
                foreach ($productConcretePageSearchTransfersLocales as $productConcretePageSearchTransfer) {
                    $this->productConcretePageSearchWriter->deleteProductConcretePageSearch($productConcretePageSearchTransfer);
                }
            }
        }
    }
}
