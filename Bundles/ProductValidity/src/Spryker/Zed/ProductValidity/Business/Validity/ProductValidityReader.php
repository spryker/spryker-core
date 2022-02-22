<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business\Validity;

use Spryker\Zed\ProductValidity\Persistence\ProductValidityRepositoryInterface;

class ProductValidityReader implements ProductValidityReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductValidity\Persistence\ProductValidityRepositoryInterface
     */
    protected $productValidityRepository;

    /**
     * @param \Spryker\Zed\ProductValidity\Persistence\ProductValidityRepositoryInterface $productValidityRepository
     */
    public function __construct(
        ProductValidityRepositoryInterface $productValidityRepository
    ) {
        $this->productValidityRepository = $productValidityRepository;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithValidity(array $productConcreteTransfers): array
    {
        $productIds = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productIds[] = $productConcreteTransfer->getIdProductConcreteOrFail();
        }

        $productValidityTransfers = $this->productValidityRepository->getProductValidityTransfersIndexedByIdProductConcrete($productIds);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productValidityTransfer = $productValidityTransfers[$productConcreteTransfer->getIdProductConcreteOrFail()] ?? null;
            if ($productValidityTransfer === null) {
                continue;
            }

            $productConcreteTransfer->setValidFrom($productValidityTransfer->getValidFrom());
            $productConcreteTransfer->setValidTo($productValidityTransfer->getValidTo());
        }

        return $productConcreteTransfers;
    }
}
