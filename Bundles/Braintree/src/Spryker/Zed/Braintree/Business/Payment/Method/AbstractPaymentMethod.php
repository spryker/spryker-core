<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Method;

use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Braintree\BraintreeConfig;

abstract class AbstractPaymentMethod
{

    const BRAINTREE_DATE_FORMAT = 'Y-m-d';

    /**
     * @var \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(BraintreeConfig $config)
    {
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
     * @return string
     */
    abstract public function getMethodType();

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return string
     */
    protected function formatAddress($addressTransfer)
    {
        return trim(sprintf(
            '%s %s %s',
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3()
        ));
    }

}
