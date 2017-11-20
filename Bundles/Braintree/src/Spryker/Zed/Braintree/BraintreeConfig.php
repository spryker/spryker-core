<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree;

use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class BraintreeConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->get(BraintreeConstants::PUBLIC_KEY);
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->get(BraintreeConstants::PRIVATE_KEY);
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->get(BraintreeConstants::MERCHANT_ID);
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->get(BraintreeConstants::ENVIRONMENT);
    }

    /**
     * @return bool
     */
    public function getIsVaulted()
    {
        return $this->get(BraintreeConstants::IS_VAULTED, false);
    }

    /**
     * @return bool
     */
    public function getIs3DSecure()
    {
        return $this->get(BraintreeConstants::IS_3D_SECURE, false);
    }

    /**
     * @return string
     */
    final public function getChannel()
    {
        return 'Spryker_BT_DE';
    }
}
