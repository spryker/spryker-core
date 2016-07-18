<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Handler;

use Spryker\Zed\Braintree\BraintreeConfig;

abstract class AbstractPaymentHandler
{

    /**
     * @var \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Braintree\BraintreeConfig $config
     */
    public function __construct(
        BraintreeConfig $config
    ) {
        $this->config = $config;
    }

    /**
     * @return \Spryker\Zed\Braintree\BraintreeConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

}
