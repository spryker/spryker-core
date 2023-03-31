<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Exception;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor\PriceProductScheduleApplyTransactionExecutorInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleApplier implements PriceProductScheduleApplierInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface
     */
    protected $priceProductScheduleDisabler;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor\PriceProductScheduleApplyTransactionExecutorInterface
     */
    protected $applyScheduledPriceTransactionExecutor;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface $priceProductScheduleDisabler
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Executor\PriceProductScheduleApplyTransactionExecutorInterface $applyScheduledPriceTransactionExecutor
     */
    public function __construct(
        PriceProductScheduleDisablerInterface $priceProductScheduleDisabler,
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleToStoreFacadeInterface $storeFacade,
        PriceProductScheduleApplyTransactionExecutorInterface $applyScheduledPriceTransactionExecutor
    ) {
        $this->priceProductScheduleDisabler = $priceProductScheduleDisabler;
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->storeFacade = $storeFacade;
        $this->applyScheduledPriceTransactionExecutor = $applyScheduledPriceTransactionExecutor;
    }

    /**
     * @param string|null $storeName
     *
     * @return void
     */
    public function applyScheduledPrices(?string $storeName = null): void
    {
        $productSchedulePricesForEnable = $this->resolvePriceProductSchedulesToEnable($storeName);

        $this->applyScheduledPriceTransactionExecutor->execute($productSchedulePricesForEnable);

        $this->priceProductScheduleDisabler->disableNotActiveScheduledPrices();
    }

    /**
     * @param string|null $storeName
     *
     * @throws \Exception
     *
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    protected function resolvePriceProductSchedulesToEnable(?string $storeName = null): array
    {
        if ($storeName) {
            $storeTransfer = $this->storeFacade->findStoreByName($storeName);

            if (!$storeTransfer) {
                throw new Exception("Store $storeName not found.");
            }

            return $this->priceProductScheduleRepository->findPriceProductSchedulesToEnableByStore($storeTransfer);
        }

        if ($this->storeFacade->isDynamicStoreEnabled()) {
            $productSchedulePricesForEnable = [];

            if ($this->storeFacade->isCurrentStoreDefined()) {
                return $this->priceProductScheduleRepository->findPriceProductSchedulesToEnableByStore($this->storeFacade->getCurrentStore());
            }

            foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
                $productSchedulePricesForEnable[] = $this->priceProductScheduleRepository->findPriceProductSchedulesToEnableByStore($storeTransfer);
            }

            return array_merge(...$productSchedulePricesForEnable);
        }

        return $this->findPriceProductSchedulesToEnableForCurrentStore();
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceProductScheduleTransfer>
     */
    protected function findPriceProductSchedulesToEnableForCurrentStore(): array
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->priceProductScheduleRepository->findPriceProductSchedulesToEnableByStore($storeTransfer);
    }
}
