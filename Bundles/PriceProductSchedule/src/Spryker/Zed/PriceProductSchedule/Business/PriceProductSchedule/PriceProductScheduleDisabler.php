<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use DateTime;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductUpdaterInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleDisabler implements PriceProductScheduleDisablerInterface
{
    use TransactionTrait;

    protected const PATTERN_MINUS_ONE_DAY = '-1 day';
    protected const PATTERN_FORMAT_DATE = 'Y-m-d H:i:s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface
     */
    protected $priceProductFallbackFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductUpdaterInterface
     */
    protected $productPriceUpdater;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface $priceProductFallbackFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductUpdaterInterface $productPriceUpdater
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductFallbackFinderInterface $priceProductFallbackFinder,
        PriceProductUpdaterInterface $productPriceUpdater,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->priceProductFallbackFinder = $priceProductFallbackFinder;
        $this->productPriceUpdater = $productPriceUpdater;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @return void
     */
    public function disableNotActiveScheduledPrices(): void
    {
        $productSchedulePricesForDisable = $this->priceProductScheduleRepository->findPriceProductSchedulesToDisable();

        foreach ($productSchedulePricesForDisable as $priceProductScheduleTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
                $this->executeExitLogicTransaction($priceProductScheduleTransfer);
            });
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function disableNotActiveScheduledPricesByIdProductAbstract(int $idProductAbstract): void
    {
        $productSchedulePricesForDisable = $this->priceProductScheduleRepository
            ->findPriceProductSchedulesToDisableByIdProductAbstract($idProductAbstract);

        foreach ($productSchedulePricesForDisable as $priceProductScheduleTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
                $this->executeExitLogicTransaction($priceProductScheduleTransfer);
            });
        }
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    public function disableNotActiveScheduledPricesByIdProductConcrete(int $idProductConcrete): void
    {
        $productSchedulePricesForDisable = $this->priceProductScheduleRepository
            ->findPriceProductSchedulesToDisableByIdProductConcrete($idProductConcrete);

        foreach ($productSchedulePricesForDisable as $priceProductScheduleTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
                $this->executeExitLogicTransaction($priceProductScheduleTransfer);
            });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function disableNotRelevantPriceProductSchedulesByPriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): void {
        $productSchedulePricesForDisable = $this->priceProductScheduleRepository
            ->findSimilarPriceProductSchedulesToDisable($priceProductScheduleTransfer);

        foreach ($productSchedulePricesForDisable as $priceProductScheduleTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
                $this->executeExitLogicTransaction($priceProductScheduleTransfer);
            });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    protected function executeExitLogicTransaction(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();

        $fallbackPriceProduct = $this->priceProductFallbackFinder->findFallbackPriceProduct($priceProductTransfer);

        $priceProductScheduleTransfer->setIsCurrent(false);

        $this->priceProductScheduleEntityManager->savePriceProductSchedule($priceProductScheduleTransfer);

        if ($fallbackPriceProduct !== null) {
            if ($priceProductTransfer->getSkuProduct() !== null) {
                $fallbackPriceProduct->setSkuProduct($priceProductTransfer->getSkuProduct());
            }

            if ($priceProductTransfer->getSkuProductAbstract() !== null) {
                $fallbackPriceProduct->setSkuProductAbstract($priceProductTransfer->getSkuProductAbstract());
            }

            $this->productPriceUpdater->updateCurrentPriceProduct(
                $fallbackPriceProduct,
                $priceProductTransfer->getPriceType()
            );

            return;
        }

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setPriceTypeName($priceProductTransfer->getPriceTypeName())
            ->setCurrencyIsoCode($priceProductTransfer->getMoneyValue()->getCurrency()->getCode());

        if ($priceProductTransfer->getSkuProduct() !== null) {
            $priceProductFilterTransfer->setSku($priceProductTransfer->getSkuProduct());
        }

        if ($priceProductTransfer->getSkuProductAbstract() !== null) {
            $priceProductFilterTransfer->setSku($priceProductTransfer->getSkuProductAbstract());
        }

        $currentPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        if ($currentPriceProductTransfer === null) {
            return;
        }

        $this->priceProductFacade->removePriceProductDefaultForPriceProduct($currentPriceProductTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function disablePriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleTransfer
    {
        $dateInThePast = new DateTime(static::PATTERN_MINUS_ONE_DAY);
        $priceProductScheduleTransfer->setActiveTo($dateInThePast->format(static::PATTERN_FORMAT_DATE));

        return $this->priceProductScheduleEntityManager->savePriceProductSchedule($priceProductScheduleTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function deactivatePriceProductSchedule(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductScheduleTransfer->setIsCurrent(false);

        return $this->priceProductScheduleEntityManager
            ->savePriceProductSchedule($priceProductScheduleTransfer);
    }
}
