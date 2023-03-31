<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

class LocaleValidator implements LocaleValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EMPTY_DEFAULT_LOCALE = 'Default locale must not be empty.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_INVALID_DEFAULT_LOCALE = 'Default locale must be presented in available locales.';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validateStoreLocale(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer())->setStore($storeTransfer)
            ->setIsSuccessful(true);

        $localeIsoCodeOrFail = $storeTransfer->getDefaultLocaleIsoCode();
        if (!$localeIsoCodeOrFail) {
            return (new StoreResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage((new MessageTransfer())->setValue(static::ERROR_MESSAGE_EMPTY_DEFAULT_LOCALE));
        }

        if (in_array($localeIsoCodeOrFail, $storeTransfer->getAvailableLocaleIsoCodes(), true)) {
            return $storeResponseTransfer;
        }

        return (new StoreResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage((new MessageTransfer())->setValue(static::ERROR_MESSAGE_INVALID_DEFAULT_LOCALE));
    }
}
