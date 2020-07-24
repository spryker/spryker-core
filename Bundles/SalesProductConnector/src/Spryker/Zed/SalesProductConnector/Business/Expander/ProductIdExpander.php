<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Expander;

use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface;

class ProductIdExpander implements ProductIdExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface
     */
    protected $salesProductConnectorRepository;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepositoryInterface $salesProductConnectorRepository
     */
    public function __construct(SalesProductConnectorRepositoryInterface $salesProductConnectorRepository)
    {
        $this->salesProductConnectorRepository = $salesProductConnectorRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandOrderItemsWithProduct(array $itemTransfers): array
    {
        $productConcreteSkus = $this->extractProductConcreteSkus($itemTransfers);
        $productConcreteTransfers = $this->salesProductConnectorRepository->getRawProductConcreteTransfersByConcreteSkus($productConcreteSkus);

        $mappedProductConcreteTransfers = $this->mapProductConcreteTransfersBySku($productConcreteTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteTransfer = $mappedProductConcreteTransfers[$itemTransfer->getSku()] ?? null;

            if (!$productConcreteTransfer) {
                continue;
            }

            $itemTransfer->setId($productConcreteTransfer->getIdProductConcrete());
            $itemTransfer->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function extractProductConcreteSkus(array $itemTransfers): array
    {
        $productConcreteSkus = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return array_unique($productConcreteSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function mapProductConcreteTransfersBySku(array $productConcreteTransfers): array
    {
        $mappedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $mappedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $mappedProductConcreteTransfers;
    }
}
