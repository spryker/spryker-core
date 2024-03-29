<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface;

class ProductQuantityReader implements ProductQuantityReaderInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_INTERVAL = 1;

    /**
     * @var \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface
     */
    protected $productQuantityRepository;

    /**
     * @param \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface $productQuantityRepository
     */
    public function __construct(ProductQuantityRepositoryInterface $productQuantityRepository)
    {
        $this->productQuantityRepository = $productQuantityRepository;
    }

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfersByProductIds(array $productIds): array
    {
        $productQuantityTransfers = $this->productQuantityRepository->findProductQuantityTransfersByProductIds($productIds);

        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $this->filterProductQuantityTransfer($productQuantityTransfer);
        }

        return $productQuantityTransfers;
    }

    /**
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfers(): array
    {
        $productQuantityTransfers = $this->productQuantityRepository->findProductQuantityTransfers();

        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $this->filterProductQuantityTransfer($productQuantityTransfer);
        }

        return $productQuantityTransfers;
    }

    /**
     * @param array<string> $productSkus
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfersByProductSku(array $productSkus): array
    {
        $productQuantityTransfers = $this->productQuantityRepository->findProductQuantityTransfersByProductSku($productSkus);

        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $this->filterProductQuantityTransfer($productQuantityTransfer);
        }

        return $productQuantityTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findFilteredProductQuantityTransfers(FilterTransfer $filterTransfer): array
    {
        $productQuantityTransfers = $this->productQuantityRepository
            ->findFilteredProductQuantityTransfers($filterTransfer);

        foreach ($productQuantityTransfers as $productQuantityTransfer) {
            $this->filterProductQuantityTransfer($productQuantityTransfer);
        }

        return $productQuantityTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return void
     */
    protected function filterProductQuantityTransfer(ProductQuantityTransfer $productQuantityTransfer): void
    {
        if ($productQuantityTransfer->getQuantityInterval() === null) {
            $productQuantityTransfer->setQuantityInterval(static::DEFAULT_INTERVAL);
        }

        if ($productQuantityTransfer->getQuantityMin() === null) {
            $productQuantityTransfer->setQuantityMin($productQuantityTransfer->getQuantityInterval());
        }
    }
}
