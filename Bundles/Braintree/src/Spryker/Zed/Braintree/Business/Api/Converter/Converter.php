<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Api\Converter;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface;

class Converter implements ConverterInterface
{

    /**
     * @param string $stringData
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
     */
    public function toTransactionResponseTransfer($stringData)
    {
        $arrayData = $this->stringToArray($stringData);
        $transactionResponseTransfer = $this->arrayToTransactionResponseTransfer($arrayData);

        return $transactionResponseTransfer;
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function centsToDecimal($amount)
    {
        return CurrencyManager::getInstance()->convertDecimalToCent($amount);
    }

}
