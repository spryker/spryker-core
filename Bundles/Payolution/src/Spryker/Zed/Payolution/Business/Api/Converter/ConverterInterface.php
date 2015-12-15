<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Api\Converter;

use Generated\Shared\Transfer\PayolutionTransactionResponseTransfer;
use Generated\Shared\Transfer\PayolutionCalculationResponseTransfer;

interface ConverterInterface
{

    /**
     * @param string $stringData
     *
     * @return PayolutionTransactionResponseTransfer
     */
    public function toTransactionResponseTransfer($stringData);

    /**
     * @param array $data
     *
     * @return string
     */
    public function toCalculationRequest(array $data);

    /**
     * @param string $stringData
     *
     * @return PayolutionCalculationResponseTransfer
     */
    public function toCalculationResponseTransfer($stringData);

}
