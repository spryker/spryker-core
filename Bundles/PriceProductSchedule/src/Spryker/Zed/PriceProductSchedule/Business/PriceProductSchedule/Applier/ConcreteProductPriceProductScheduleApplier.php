<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Applier;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor\PriceProductScheduleApplyTransactionExecutorInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class ConcreteProductPriceProductScheduleApplier implements ConcreteProductPriceProductScheduleApplierInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor\PriceProductScheduleApplyTransactionExecutorInterface
     */
    protected $applyScheduledPriceTransactionExecutor;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface
     */
    protected $priceProductScheduleDisabler;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor\PriceProductScheduleApplyTransactionExecutorInterface $applyScheduledPriceTransactionExecutor
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface $priceProductScheduleDisabler
     */
    public function __construct(
        PriceProductScheduleToStoreFacadeInterface $storeFacade,
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleApplyTransactionExecutorInterface $applyScheduledPriceTransactionExecutor,
        PriceProductScheduleDisablerInterface $priceProductScheduleDisabler
    ) {
        $this->storeFacade = $storeFacade;
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->applyScheduledPriceTransactionExecutor = $applyScheduledPriceTransactionExecutor;
        $this->priceProductScheduleDisabler = $priceProductScheduleDisabler;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function applyScheduledPrices(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $priceProductScheduleTransfer->requirePriceProduct();
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();
        $priceProductTransfer->requireIdProduct();
        $this->priceProductScheduleDisabler
            ->disableNotActiveScheduledPricesByIdProductConcrete($priceProductTransfer->getIdProduct());
        $priceProductScheduleTransfer = $this->priceProductScheduleDisabler
            ->deactivatePriceProductSchedule($priceProductScheduleTransfer);

        $priceProductScheduleTransferCollection = $this->findPriceProductSchedulesToEnableForCurrentStoreAndIdProductConcrete($priceProductScheduleTransfer);
        $this->applyScheduledPriceTransactionExecutor
            ->execute($priceProductScheduleTransferCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    protected function findPriceProductSchedulesToEnableForCurrentStoreAndIdProductConcrete(PriceProductScheduleTransfer $priceProductScheduleTransfer): array
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->priceProductScheduleRepository
            ->findPriceProductSchedulesToEnableByStoreAndIdProductConcrete(
                $storeTransfer,
                $priceProductScheduleTransfer->getPriceProduct()->getIdProduct()
            );
    }
}
