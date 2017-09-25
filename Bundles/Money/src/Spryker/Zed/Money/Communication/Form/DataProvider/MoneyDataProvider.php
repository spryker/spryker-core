<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CurrencyTransfer;

class MoneyDataProvider
{

    const DEFAULT_SCALE = 2;

    /**
     * @param mixed $moneyValueTransfer
     *
     * @return mixed
     */
    public function getMoneyCurrencyOptionsFor($moneyValueTransfer)
    {
        $currencyTransfer = $moneyValueTransfer->getCurrency();
        $options['divisor'] = $this->getDivisor($currencyTransfer);
        $options['scale'] = $this->getScale($currencyTransfer);

        return $options;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getDivisor(CurrencyTransfer $currencyTransfer)
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        $divisor = 1;
        if ($fractionDigits) {
            $divisor = pow(10, $fractionDigits);
        }

        return $divisor;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return int
     */
    protected function getScale(CurrencyTransfer $currencyTransfer)
    {
        $fractionDigits = $currencyTransfer->getFractionDigits();

        if ($fractionDigits !== null) {
            return $fractionDigits;
        }

        return static::DEFAULT_SCALE;
    }

}
