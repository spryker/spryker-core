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

class ProductPriceUpdater implements ProductPriceUpdaterInterface
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $fallbackPriceProductTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $currentPriceType
     *
     * @return void
     */
    public function updateCurrentProductPrice(
        PriceProductTransfer $fallbackPriceProductTransfer,
        PriceTypeTransfer $currentPriceType
    ): void {
        $fallbackMoneyValueTransfer = $fallbackPriceProductTransfer->getMoneyValue();
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($fallbackPriceProductTransfer->getSkuProduct())
            ->setPriceTypeName($currentPriceType->getName())
            ->setCurrencyIsoCode($fallbackMoneyValueTransfer->getCurrency()->getCode());

        $priceProductTransferForUpdate = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);

        if ($priceProductTransferForUpdate === null) {
            return;
        }

        $priceProductTransferForUpdate->getMoneyValue()->setGrossAmount($fallbackMoneyValueTransfer->getGrossAmount());
        $priceProductTransferForUpdate->getMoneyValue()->setNetAmount($fallbackMoneyValueTransfer->getNetAmount());

        $this->priceProductFacade->persistPriceProductStore($priceProductTransferForUpdate);
    }
}
