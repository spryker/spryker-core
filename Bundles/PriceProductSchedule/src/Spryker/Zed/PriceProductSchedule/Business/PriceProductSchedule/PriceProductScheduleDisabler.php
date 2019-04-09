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
use Spryker\Zed\PriceProductSchedule\Business\PriceProduct\ProductPriceUpdaterInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;

class PriceProductScheduleDisabler implements PriceProductScheduleDisablerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface
     */
    protected $priceProductScheduleWriter;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleReaderInterface
     */
    protected $priceProductScheduleReader;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface
     */
    protected $priceProductFallbackFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\ProductPriceUpdaterInterface
     */
    protected $productPriceUpdater;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface $priceProductScheduleWriter
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleReaderInterface $priceProductScheduleReader
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\PriceProductFallbackFinderInterface $priceProductFallbackFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProduct\ProductPriceUpdaterInterface $productPriceUpdater
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleWriterInterface $priceProductScheduleWriter,
        PriceProductScheduleReaderInterface $priceProductScheduleReader,
        PriceProductFallbackFinderInterface $priceProductFallbackFinder,
        ProductPriceUpdaterInterface $productPriceUpdater,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductScheduleWriter = $priceProductScheduleWriter;
        $this->priceProductScheduleReader = $priceProductScheduleReader;
        $this->priceProductFallbackFinder = $priceProductFallbackFinder;
        $this->productPriceUpdater = $productPriceUpdater;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @return void
     */
    public function disableNotActiveScheduledPrices(): void
    {
        $productSchedulePricesForDisable = $this->priceProductScheduleReader->findPriceProductSchedulesToDisable();

        foreach ($productSchedulePricesForDisable as $priceProductScheduleTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
                $this->executeFallbackLogic($priceProductScheduleTransfer);
            });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function disableOtherSimilarPriceProductSchedules(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $productSchedulePricesForDisable = $this->priceProductScheduleReader->findPriceProductSchedulesToDisable();

        foreach ($productSchedulePricesForDisable as $priceProductScheduleTransfer) {
            $this->executeFallbackLogic($priceProductScheduleTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    protected function executeFallbackLogic(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $fallbackPriceProduct = $this->priceProductFallbackFinder->findFallbackPriceProduct($priceProductScheduleTransfer->getPriceProduct());

        $priceProductScheduleTransfer->setIsCurrent(false);

        $this->priceProductScheduleWriter->savePriceProductSchedule($priceProductScheduleTransfer);

        if ($fallbackPriceProduct !== null) {
            $fallbackPriceProduct->setSkuProduct($priceProductScheduleTransfer->getPriceProduct()->getSkuProduct());
            $this->productPriceUpdater->updateCurrentProductPrice($fallbackPriceProduct, $priceProductScheduleTransfer->getPriceProduct()->getPriceType());

            return;
        }

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($priceProductScheduleTransfer->getPriceProduct()->getSkuProduct())
            ->setPriceTypeName($priceProductScheduleTransfer->getPriceProduct()->getPriceTypeName())
            ->setCurrencyIsoCode($priceProductScheduleTransfer->getPriceProduct()->getMoneyValue()->getCurrency()->getCode());

        $currentPriceProductTransfer = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        if ($currentPriceProductTransfer === null) {
            return;
        }

        $this->priceProductFacade->removePriceProductStore($currentPriceProductTransfer);
    }
}
