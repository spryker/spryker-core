<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Converter;

use Generated\Shared\Payolution\PayolutionTransactionResponseInterface;
use Generated\Shared\Payolution\PayolutionCalculationResponseInterface;

interface ConverterInterface
{

    /**
     * @param string $stringData
     *
     * @return PayolutionTransactionResponseInterface
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
     * @return PayolutionCalculationResponseInterface
     */
    public function toCalculationResponseTransfer($stringData);

}
