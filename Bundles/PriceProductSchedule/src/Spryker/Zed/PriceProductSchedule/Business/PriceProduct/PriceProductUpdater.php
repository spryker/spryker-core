<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductUpdater implements PriceProductUpdaterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $currentPriceType
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function updateCurrentPriceProduct(
        PriceProductTransfer $priceProductTransfer,
        PriceTypeTransfer $currentPriceType
    ): ?PriceProductTransfer {
        $priceProductTransfer->requireMoneyValue();
        $fallbackMoneyValueTransfer = $priceProductTransfer->getMoneyValue();
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setPriceTypeName($currentPriceType->getName())
            ->setCurrencyIsoCode($fallbackMoneyValueTransfer->getCurrency()->getCode());

        if ($priceProductTransfer->getSkuProductAbstract() !== null) {
            $priceProductFilterTransfer->setSku($priceProductTransfer->getSkuProductAbstract());
        }

        if ($priceProductTransfer->getSkuProduct() !== null) {
            $priceProductFilterTransfer->setSku($priceProductTransfer->getSkuProduct());
        }

        $priceProductTransferForUpdate = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        if ($priceProductTransferForUpdate === null) {
            return null;
        }

        $priceProductTransferForUpdate->getMoneyValue()
            ->setGrossAmount($fallbackMoneyValueTransfer->getGrossAmount())
            ->setNetAmount($fallbackMoneyValueTransfer->getNetAmount());

        return $this->priceProductFacade->persistPriceProductStore($priceProductTransferForUpdate);
    }
}
