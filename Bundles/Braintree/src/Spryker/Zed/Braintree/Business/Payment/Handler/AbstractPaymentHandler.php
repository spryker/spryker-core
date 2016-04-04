<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Handler;

use Spryker\Zed\Braintree\BraintreeConfig;
use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payolution\Business\Api\Converter\ConverterInterface;
use Spryker\Zed\Braintree\Business\Exception\NoMethodMapperException;
use Spryker\Zed\Braintree\Business\Exception\OrderGrandTotalException;

abstract class AbstractPaymentHandler
{

    /**
     * @var \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $methodMappers = [];

    /**
     * @param \Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(
        AdapterInterface $executionAdapter,
        BraintreeConfig $config
    ) {
        $this->executionAdapter = $executionAdapter;
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Spryker\Zed\Braintree\Business\Payment\Method\PayPal\PaypalInterface $mapper
     *
     * @return void
     */
    public function registerMethodMapper($mapper)
    {
        $this->methodMappers[$mapper->getAccountBrand()] = $mapper;

        file_put_contents('xxx.log', print_r($this->methodMappers, true));
    }

    /**
     * @param string $accountBrand
     *
     * @throws \Spryker\Zed\Braintree\Business\Exception\NoMethodMapperException
     *
     * @return \Spryker\Zed\Braintree\Business\Payment\Method\PayPal\PayPalInterface
     */
    protected function getMethodMapper($accountBrand)
    {
        file_put_contents('xxx2.log', print_r($accountBrand, true));

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
     * @throws \Spryker\Zed\Braintree\Business\Exception\OrderGrandTotalException
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

}
