<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException;
use Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapperInterface;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface;

class ProductOfferStockReader implements ProductOfferStockReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface
     */
    protected $productOfferStockRepository;

    /**
     * @var \Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapperInterface
     */
    protected $productOfferStockResultMapper;

    /**
     * @var array<\Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin\StockTransferProductOfferStockExpanderPluginInterface>
     */
    protected $stockTransferProductOfferStockExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface $productOfferStockRepository
     * @param \Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapperInterface $productOfferStockResultMapper
     * @param array<\Spryker\Zed\ProductOfferStockExtension\Dependency\Plugin\StockTransferProductOfferStockExpanderPluginInterface> $stockTransferProductOfferStockExpanderPlugins
     */
    public function __construct(
        ProductOfferStockRepositoryInterface $productOfferStockRepository,
        ProductOfferStockResultMapperInterface $productOfferStockResultMapper,
        array $stockTransferProductOfferStockExpanderPlugins
    ) {
        $this->productOfferStockRepository = $productOfferStockRepository;
        $this->productOfferStockResultMapper = $productOfferStockResultMapper;
        $this->stockTransferProductOfferStockExpanderPlugins = $stockTransferProductOfferStockExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockResultTransfer
     */
    public function getProductOfferStockResult(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ProductOfferStockResultTransfer
    {
        $productOfferStockRequestTransfer
            ->requireProductOfferReference()
            ->getStoreOrFail()
            ->requireName();

        $productOfferStockTransfers = $this->productOfferStockRepository->find($productOfferStockRequestTransfer);

        $productOfferStockResultTransfer = $this->productOfferStockResultMapper
            ->mapProductOfferStockTransfersToProductOfferStockResultTransfer(
                $productOfferStockTransfers,
            );

        return $productOfferStockResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @throws \Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferNotFoundException
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>
     */
    public function getProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ArrayObject
    {
        $productOfferStockRequestTransfer->requireProductOfferReference()
            ->requireStore()
            ->getStoreOrFail()
            ->requireName();

        $productOfferStockTransfers = $this->productOfferStockRepository->find($productOfferStockRequestTransfer);

        if (!$productOfferStockTransfers->count()) {
            throw new ProductOfferNotFoundException(
                sprintf(
                    'Product offer stock with product reference: %s, not found',
                    $productOfferStockRequestTransfer->getProductOfferReferenceOrFail(),
                ),
            );
        }

        return $productOfferStockTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferStockTransfer>|null
     */
    public function findProductOfferStocks(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): ?ArrayObject
    {
        $productOfferStockRequestTransfer->requireProductOfferReference()
            ->requireStore()
            ->getStoreOrFail()
            ->requireName();

        $productOfferStockTransfers = $this->productOfferStockRepository->find($productOfferStockRequestTransfer);

        if (!$productOfferStockTransfers->count()) {
            return null;
        }

        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            $this->executeStockTransferExpanderPlugins($productOfferStockTransfer->getStockOrFail());
        }

        return $productOfferStockTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    protected function executeStockTransferExpanderPlugins(StockTransfer $stockTransfer): StockTransfer
    {
        foreach ($this->stockTransferProductOfferStockExpanderPlugins as $expanderPlugin) {
            $stockTransfer = $expanderPlugin->expand($stockTransfer);
        }

        return $stockTransfer;
    }
}
