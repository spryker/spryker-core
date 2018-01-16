<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Payment\Handler;

use Spryker\Shared\Payolution\PayolutionConfig;
use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Payolution\Business\Exception\NoMethodMapperException;
use Spryker\Zed\Payolution\Business\Exception\OrderGrandTotalException;

abstract class AbstractPaymentHandler
{
    /**
     * @var \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface
     */
    protected $converter;

    /**
     * @var \Spryker\Zed\Payolution\PayolutionConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $methodMappers = [];

    /**
     * @param \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface $converter
     * @param \Spryker\Zed\Payolution\PayolutionConfig $config
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        ConverterInterface $converter,
        PayolutionConfig $config
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->converter = $converter;
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\Payolution\PayolutionConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Spryker\Zed\Payolution\Business\Payment\Method\Invoice\InvoiceInterface|\Spryker\Zed\Payolution\Business\Payment\Method\Installment\InstallmentInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper($mapper)
    {
        $this->methodMappers[$mapper->getAccountBrand()] = $mapper;
    }

    /**
     * @param string $accountBrand
     *
     * @throws \Spryker\Zed\Payolution\Business\Exception\NoMethodMapperException
     *
     * @return \Spryker\Zed\Payolution\Business\Payment\Method\Invoice\InvoiceInterface|\Spryker\Zed\Payolution\Business\Payment\Method\Installment\InstallmentInterface
     */
    protected function getMethodMapper($accountBrand)
    {
        if (isset($this->methodMappers[$accountBrand]) === false) {
            throw new NoMethodMapperException('The method mapper is not registered.');
        }

        return $this->methodMappers[$accountBrand];
    }

    /**
     * @param int $amount
     * @param int $min
     * @param int $max
     *
     * @throws \Spryker\Zed\Payolution\Business\Exception\OrderGrandTotalException
     *
     * @return void
     */
    protected function checkMaxMinGrandTotal($amount, $min, $max)
    {
        if ($amount < $min) {
            throw new OrderGrandTotalException('The grand total is less than the allowed minimum amount');
        }

        if ($amount > $max) {
            throw new OrderGrandTotalException('The grand total is greater than the allowed maximum amount');
        }
    }

    /**
     * @param array|string $requestData
     *
     * @return \Generated\Shared\Transfer\PayolutionTransactionResponseTransfer|\Generated\Shared\Transfer\PayolutionCalculationResponseTransfer
     */
    abstract protected function sendRequest($requestData);
}
