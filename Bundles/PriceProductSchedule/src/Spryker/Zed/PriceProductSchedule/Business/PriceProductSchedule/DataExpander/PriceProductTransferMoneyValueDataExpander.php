<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Currency\Business\Model\Exception\CurrencyNotFoundException;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;

class PriceProductTransferMoneyValueDataExpander implements PriceProductTransferDataExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        PriceProductScheduleToStoreFacadeInterface $storeFacade,
        PriceProductScheduleToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @throws \Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function expand(
        PriceProductTransfer $priceProductTransfer,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductTransfer {
        try {
            $currencyTransfer = $this->currencyFacade->fromIsoCode($priceProductScheduleImportTransfer->getCurrencyCode());
        } catch (CurrencyNotFoundException $e) {
            throw new PriceProductScheduleListImportException($e);
        }

        try {
            $storeTransfer = $this->storeFacade->getStoreByName($priceProductScheduleImportTransfer->getStoreName());
        } catch (StoreNotFoundException $e) {
            throw new PriceProductScheduleListImportException($e);
        }

        $moneyValueTransfer =
            (new MoneyValueTransfer())
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true)
            ->setCurrency($currencyTransfer)
            ->setFkCurrency($currencyTransfer->getIdCurrency())
            ->setFkStore($storeTransfer->getIdStore());

        return $priceProductTransfer->setMoneyValue($moneyValueTransfer);
    }
}
