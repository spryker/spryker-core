<?php

namespace SprykerFeature\Zed\Payone\Business;

use SprykerFeature\Shared\Payone\PayoneConfig;
use SprykerFeature\Shared\Library\Config;


class PayoneSettings
{

    /**
     * @return string
     */
    public function getRedirectSuccessUrl()
    {
        return '/checkout/success/';
    }

    /**
     * @return string
     */
    public function getRedirectErrorUrl()
    {
        return '/checkout/index/';
    }

    /**
     * @return string
     */
    public function getRedirectBackUrl()
    {
        return '/checkout/regular-redirect-payment-cancellation/';
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getCredentials()
    {
        return Config::get(PayoneConfig::PAYONE_CREDENTIALS);
    }

}
