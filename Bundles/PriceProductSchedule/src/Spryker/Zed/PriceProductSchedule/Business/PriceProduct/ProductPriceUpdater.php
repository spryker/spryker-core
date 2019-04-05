<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
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
     *
     * @return void
     */
    public function updateCurrentProductPrice(PriceProductTransfer $fallbackPriceProductTransfer): void
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setSku($fallbackPriceProductTransfer->getSkuProduct())
            ->setCurrencyIsoCode($fallbackPriceProductTransfer->getMoneyValue()->getCurrency()->getCode());

        $priceProductTransferForUpdate = $this->priceProductFacade->findPriceProductFor($priceProductFilterTransfer);
        $priceProductTransferForUpdate->getMoneyValue()->setGrossAmount($fallbackPriceProductTransfer->getMoneyValue()->getGrossAmount());
        $priceProductTransferForUpdate->getMoneyValue()->setGrossAmount($fallbackPriceProductTransfer->getMoneyValue()->getNetAmount());

        $this->priceProductFacade->persistPriceProductStore($priceProductTransferForUpdate);
    }
}
