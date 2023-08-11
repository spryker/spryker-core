<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStock\Business\Expander;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface;

class MerchantStockMerchantExpander implements MerchantStockMerchantExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface
     */
    protected MerchantStockRepositoryInterface $merchantStockRepository;

    /**
     * @param \Spryker\Zed\MerchantStock\Persistence\MerchantStockRepositoryInterface $merchantStockRepository
     */
    public function __construct(
        MerchantStockRepositoryInterface $merchantStockRepository
    ) {
        $this->merchantStockRepository = $merchantStockRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        if ($merchantCollectionTransfer->getMerchants()->count() === 0) {
            return $merchantCollectionTransfer;
        }

        $merchantIds = $this->getMerchantIds($merchantCollectionTransfer);
        $stockTransfersGroupedByIdMerchant = $this->merchantStockRepository->getStocksGroupedByIdMerchant($merchantIds);

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $idMerchant = $merchantTransfer->getIdMerchantOrFail();

            if (
                !isset($stockTransfersGroupedByIdMerchant[$idMerchant])
                || $stockTransfersGroupedByIdMerchant[$idMerchant]->count() === 0
            ) {
                continue;
            }

            $merchantTransfer->setStocks($stockTransfersGroupedByIdMerchant[$idMerchant]);
        }

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return list<int>
     */
    protected function getMerchantIds(MerchantCollectionTransfer $merchantCollectionTransfer): array
    {
        $merchantIds = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantIds[] = $merchantTransfer->getIdMerchantOrFail();
        }

        return $merchantIds;
    }
}
