<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class StoreCurrencyValidator implements StoreCurrencyValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DEFAULT_CURRENCY_IS_NOT_IN_AVAILABLE_CURRENCIES = 'Default currency currency_code must be selected from the list of available currencies.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAM_CURRENCY_CODE = 'currency_code';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validateStoreCurrencies(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer());

        if (in_array($storeTransfer->getDefaultCurrencyIsoCodeOrFail(), $storeTransfer->getAvailableCurrencyIsoCodes(), true)) {
            return $storeResponseTransfer->setIsSuccessful(true);
        }

        return $storeResponseTransfer->setIsSuccessful(false)
            ->addMessage(
                (new MessageTransfer())
                    ->setValue(static::ERROR_MESSAGE_DEFAULT_CURRENCY_IS_NOT_IN_AVAILABLE_CURRENCIES)
                    ->setParameters([
                        static::ERROR_MESSAGE_PARAM_CURRENCY_CODE => $storeTransfer->getDefaultCurrencyIsoCodeOrFail(),
                    ]),
            );
    }
}
