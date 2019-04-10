<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;

class PriceProductScheduleApplier implements PriceProductScheduleApplierInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface
     */
    protected $priceProductScheduleWriter;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface
     */
    protected $priceProductScheduleDisabler;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleReaderInterface
     */
    protected $priceProductScheduleReader;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface $priceProductScheduleWriter
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface $priceProductScheduleDisabler
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleReaderInterface $priceProductScheduleReader
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleWriterInterface $priceProductScheduleWriter,
        PriceProductScheduleDisablerInterface $priceProductScheduleDisabler,
        PriceProductScheduleReaderInterface $priceProductScheduleReader,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductScheduleWriter = $priceProductScheduleWriter;
        $this->priceProductScheduleDisabler = $priceProductScheduleDisabler;
        $this->priceProductScheduleReader = $priceProductScheduleReader;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @return void
     */
    public function applyScheduledPrices(): void
    {
        $productSchedulePricesForEnable = $this->priceProductScheduleReader->findPriceProductSchedulesToEnableForCurrentStore();

        foreach ($productSchedulePricesForEnable as $priceProductScheduleTransfer) {
            $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
                $this->executeApplyScheduledPrices($priceProductScheduleTransfer);
            });
        }

        $this->priceProductScheduleDisabler->disableNotActiveScheduledPrices();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    protected function executeApplyScheduledPrices(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $this->priceProductFacade->persistPriceProductStore($priceProductScheduleTransfer->getPriceProduct());

        $this->priceProductScheduleDisabler->disableOtherSimilarPriceProductSchedules($priceProductScheduleTransfer);

        $priceProductScheduleTransfer->setIsCurrent(true);

        $this->priceProductScheduleWriter->savePriceProductSchedule($priceProductScheduleTransfer);
    }
}
