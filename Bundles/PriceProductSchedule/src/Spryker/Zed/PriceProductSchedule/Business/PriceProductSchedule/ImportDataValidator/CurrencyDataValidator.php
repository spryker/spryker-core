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
    protected const ERROR_MESSAGE_CURRENCY_NOT_FOUND = 'Currency was not found by provided iso code %isoCode%.';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface
     */
    protected $currencyFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\Currency\CurrencyFinderInterface $currencyFinder
     */
    public function __construct(
        CurrencyFinderInterface $currencyFinder
    ) {
        $this->currencyFinder = $currencyFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isCurrencyValid($priceProductScheduleImportTransfer) === true) {
            return null;
        }

        return $this->createPriceProductScheduleListImportErrorTransfer(
            $priceProductScheduleImportTransfer,
            static::ERROR_MESSAGE_CURRENCY_NOT_FOUND,
            ['%isoCode%' => $priceProductScheduleImportTransfer->getCurrencyCode()]
        );
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

        $currencyTransfer = $this->currencyFinder
            ->findCurrencyByIsoCode($priceProductScheduleImportTransfer->getCurrencyCode());

        return $currencyTransfer !== null;
    }
}
