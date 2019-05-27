<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter\PriceProductStoreWriterPluginExecutorInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface;

class PriceProductRemover implements PriceProductRemoverInterface
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
     * @var \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter\PriceProductStoreWriterPluginExecutorInterface
     */
    protected $priceProductStoreWriterPluginExecutor;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductRepositoryInterface $priceProductRepository
     * @param \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter\PriceProductStoreWriterPluginExecutorInterface $priceProductStoreWriterPluginExecutor
     */
    public function __construct(
        PriceProductEntityManagerInterface $priceProductEntityManager,
        PriceProductRepositoryInterface $priceProductRepository,
        PriceProductStoreWriterPluginExecutorInterface $priceProductStoreWriterPluginExecutor
    ) {
        $this->priceProductEntityManager = $priceProductEntityManager;
        $this->priceProductRepository = $priceProductRepository;
        $this->priceProductStoreWriterPluginExecutor = $priceProductStoreWriterPluginExecutor;
    }

    /**
     * {@inheritdoc}
     *
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
            $idPriceProductStore = $this->priceProductRepository->findIdPriceProductStoreByPriceProduct($priceProductTransfer);

            if ($idPriceProductStore !== null) {
                $this->priceProductStoreWriterPluginExecutor->executePriceProductStorePreDeletePlugins($idPriceProductStore);
                $this->priceProductEntityManager->deletePriceProductStoreByPriceProductTransfer($priceProductTransfer);
            }

            if ($this->priceProductRepository->isPriceProductUsedForOtherCurrencyAndStore($priceProductTransfer) === false) {
                $this->priceProductEntityManager->deletePriceProductById($priceProductTransfer->getIdPriceProduct());
            }
        });

        $this->getLogger()->warning(sprintf('Price for product with id "%s" was deleted', $priceProductTransfer->getIdPriceProduct()));
    }
}
