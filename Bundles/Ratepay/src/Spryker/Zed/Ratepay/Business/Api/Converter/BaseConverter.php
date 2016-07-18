<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Converter;

use Spryker\Shared\Library\Currency\CurrencyManager;
use \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface;

abstract class BaseConverter implements ConverterInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface
     */
    protected $response;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Model\Response\ResponseInterface $response
     * @param \Spryker\Shared\Library\Currency\CurrencyManager $currencyManager
     */
    public function __construct(
        ResponseInterface $response,
        CurrencyManager $currencyManager
    ) {

        $this->response = $response;
        $this->currencyManager = $currencyManager;
    }

    /**
     * @param float $amount
     *
     * @return int
     */
    protected function decimalToCents($amount)
    {
        return $this->currencyManager->convertDecimalToCent($amount);
    }

}
