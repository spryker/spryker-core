<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockResultTransfer;
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
     * @param \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface $productOfferStockRepository
     * @param \Spryker\Zed\ProductOfferStock\Business\Mapper\ProductOfferStockResultMapperInterface $productOfferStockResultMapper
     */
    public function __construct(
        ProductOfferStockRepositoryInterface $productOfferStockRepository,
        ProductOfferStockResultMapperInterface $productOfferStockResultMapper
    ) {
        $this->productOfferStockRepository = $productOfferStockRepository;
        $this->productOfferStockResultMapper = $productOfferStockResultMapper;
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
            ->getStore()
            ->requireName();

        $productOfferStockTransfers = $this->productOfferStockRepository->find($productOfferStockRequestTransfer);

        if (!$productOfferStockTransfers->count()) {
            throw new ProductOfferNotFoundException(
                sprintf(
                    'Product offer stock with product reference: %s, not found',
                    $productOfferStockRequestTransfer->getProductOfferReference(),
                ),
            );
        }

        return $productOfferStockTransfers;
    }
}
