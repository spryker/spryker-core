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
    public function getCalculationMode()
    {
        return $this->get(BraintreeConstants::CALCULATION_MODE);
    }

    /**
     * @return string
     */
    public function getEmailFromName()
    {
        return $this->get(BraintreeConstants::EMAIL_FROM_NAME);
    }

    /**
     * @return string
     */
    public function getEmailFromAddress()
    {
        return $this->get(BraintreeConstants::EMAIL_FROM_ADDRESS);
    }

    /**
     * @return string
     */
    public function getEmailTemplateName()
    {
        return $this->get(BraintreeConstants::EMAIL_TEMPLATE_NAME);
    }

    /**
     * @return string
     */
    public function getEmailSubject()
    {
        return $this->get(BraintreeConstants::EMAIL_SUBJECT);
    }

    /**
     * @return string
     */
    public function getWebshopUrl()
    {
        return $this->get(ApplicationConstants::HOST_YVES);
    }

}
