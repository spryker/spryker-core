<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class BraintreeConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getTransactionUserLogin()
    {
        return $this->get(BraintreeConstants::PUBLIC_KEY);
    }

    /**
     * @return string
     */
    public function getTransactionUserPassword()
    {
        return $this->get(BraintreeConstants::PRIVATE_KEY);
    }

    /**
     * @return string
     */
    public function getTransactionMode()
    {
        return $this->get(BraintreeConstants::TRANSACTION_MODE);
    }

    /**
     * @return string
     */
    public function getIsVaulted()
    {
        return $this->get(BraintreeConstants::IS_VAULTED, false);
    }

    /**
     * @return string
     */
    public function getIs3DSecure()
    {
        return $this->get(BraintreeConstants::IS_VAULTED, false);
    }

}
