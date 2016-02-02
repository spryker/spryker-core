<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Api\Converter;

interface ConverterInterface
{

    /**
     * @param string $stringData
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer
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
     * @return \Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    public function toCalculationResponseTransfer($stringData);

}
