<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\ImportDataValidator;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface;

class CurrencyDataValidator extends AbstractImportDataValidator
{
    protected const ERROR_MESSAGE_CURRENCY_NOT_FOUND = 'Currency was not found by provided iso code %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface
     */
    protected $priceProductScheduleCurrencyFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface $priceProductScheduleCurrencyFinder
     */
    public function __construct(
        CurrencyFinderInterface $priceProductScheduleCurrencyFinder
    ) {
        $this->priceProductScheduleCurrencyFinder = $priceProductScheduleCurrencyFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isCurrencyValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                sprintf(
                    static::ERROR_MESSAGE_CURRENCY_NOT_FOUND,
                    $priceProductScheduleImportTransfer->getCurrencyCode()
                )
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isCurrencyValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        if ($priceProductScheduleImportTransfer->getCurrencyCode() === null) {
            return false;
        }

        $currencyTransfer = $this->priceProductScheduleCurrencyFinder
            ->findCurrencyByIsoCode($priceProductScheduleImportTransfer->getCurrencyCode());

        if ($currencyTransfer === null) {
            return false;
        }

        return true;
    }
}
