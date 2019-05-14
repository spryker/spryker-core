<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductUpdaterInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleDisabler implements PriceProductScheduleDisablerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface
     */
    protected $priceProductScheduleWriter;

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
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface $priceProductScheduleWriter
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface $priceProductFallbackFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductUpdaterInterface $productPriceUpdater
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleWriterInterface $priceProductScheduleWriter,
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductFallbackFinderInterface $priceProductFallbackFinder,
        PriceProductUpdaterInterface $productPriceUpdater,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductScheduleWriter = $priceProductScheduleWriter;
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

        $this->priceProductScheduleWriter->savePriceProductSchedule($priceProductScheduleTransfer);

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
}
