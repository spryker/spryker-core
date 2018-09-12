<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface;

abstract class BaseConverter implements ConverterInterface
{
    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface
     */
    protected $response;

    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToMoneyInterface $moneyFacade
     */
    public function __construct(ResponseInterface $response, RatepayToMoneyInterface $moneyFacade)
    {
        $this->response = $response;
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function decimalToCents($amount)
    {
        return $this->moneyFacade->convertDecimalToInteger($amount);
    }
}
