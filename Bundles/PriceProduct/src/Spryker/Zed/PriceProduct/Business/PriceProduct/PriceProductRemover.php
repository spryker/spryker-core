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

class PriceProductRemover implements PriceProductRemoverInterface
{
    use LoggerTrait;
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $entityManager
     */
    public function __construct(PriceProductEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function removePriceProductStore(PriceProductTransfer $priceProductTransfer): void
    {
        $priceProductTransfer
            ->requireIdPriceProduct()
            ->requirePriceDimension();

        $this->getTransactionHandler()->handleTransaction(function () use ($priceProductTransfer): void {
            $this->entityManager->deletePriceProductStoreByPriceProductTransfer($priceProductTransfer);
            $this->entityManager->deletePriceProductDefaultById($priceProductTransfer->getPriceDimension()->getIdPriceProductDefault());
            $this->entityManager->deletePriceProductById($priceProductTransfer->getIdPriceProduct());
        });

        $this->getLogger()->warning(sprintf('Price for product with id "%s" was deleted', $priceProductTransfer->getIdPriceProduct()));
    }
}
