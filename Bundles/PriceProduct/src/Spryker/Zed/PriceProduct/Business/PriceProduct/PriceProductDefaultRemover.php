<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;

class PriceProductDefaultRemover implements PriceProductDefaultRemoverInterface
{
    use LoggerTrait;
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface
     */
    protected $priceProductRepository;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     */
    public function __construct(
        PriceProductEntityManagerInterface $priceProductEntityManager,
        PriceProductRepositoryInterface $priceProductRepository
    ) {
        $this->priceProductEntityManager = $priceProductEntityManager;
        $this->priceProductRepository = $priceProductRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductDefaultsForPriceProduct(PriceProductTransfer $priceProductTransfer): void
    {
        $priceProductTransfer
            ->requireIdPriceProduct()
            ->requirePriceDimension();

        $this->getTransactionHandler()->handleTransaction(function () use ($priceProductTransfer): void {
            $this->executeRemovePriceProductDefaultsForPriceProductTransaction($priceProductTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function executeRemovePriceProductDefaultsForPriceProductTransaction(
        PriceProductTransfer $priceProductTransfer
    ): void {
        $priceProductStores = $this->priceProductRepository->findPriceProductStoresByPriceProduct($priceProductTransfer);

        foreach ($priceProductStores as $priceProductStore) {
            $this->priceProductEntityManager
                ->deletePriceProductDefaultsByPriceProductStoreId($priceProductStore->getIdPriceProductStore());
        }
    }
}
