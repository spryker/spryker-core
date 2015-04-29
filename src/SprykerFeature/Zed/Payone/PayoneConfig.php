<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Payone;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;
use \SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Payone\Transfer\StandardParameterInterface;


class PayoneConfig extends AbstractBundleConfig
{

    const PAYONE_CREDENTIALS = 'PAYONE_CREDENTIALS';
    const PAYONE_CREDENTIALS_ENCODING = 'PAYONE_CREDENTIALS_ENCODING';
    const PAYONE_PAYMENT_GATEWAY_URL = 'PAYONE_PAYMENT_GATEWAY_URL';
    const PAYONE_CREDENTIALS_KEY = 'PAYONE_CREDENTIALS_KEY';
    const PAYONE_CREDENTIALS_MID = 'PAYONE_CREDENTIALS_MID';
    const PAYONE_CREDENTIALS_AID = 'PAYONE_CREDENTIALS_AID';
    const PAYONE_CREDENTIALS_PORTAL_ID = 'PAYONE_CREDENTIALS_PORTAL_ID';

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
     * @return StandardParameterInterface
     */
    public function getRequestStandardParameter()
    {
        $credentials = $this->get(self::PAYONE_CREDENTIALS);
        $standardParameter = $this->getLocator()->payone()->transferStandardParameter();

        $standardParameter->setEncoding($credentials[PayoneConfig::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameter->setMid($credentials[PayoneConfig::PAYONE_CREDENTIALS_MID]);
        $standardParameter->setAid($credentials[PayoneConfig::PAYONE_CREDENTIALS_AID]);
        $standardParameter->setPortalId($credentials[PayoneConfig::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameter->setKey($credentials[PayoneConfig::PAYONE_CREDENTIALS_KEY]);
        $standardParameter->setPaymentGatewayUrl($credentials[PayoneConfig::PAYONE_PAYMENT_GATEWAY_URL]);

        $standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

        $standardParameter->setRedirectSuccessUrl($this->get(SystemConfig::HOST_YVES) . '/' . $this->getRedirectSuccessUrl());
        $standardParameter->setRedirectBackUrl($this->get(SystemConfig::HOST_YVES) . '/' . $this->getRedirectBackUrl());
        $standardParameter->setRedirectErrorUrl($this->get(SystemConfig::HOST_YVES) . '/' . $this->getRedirectErrorUrl());

        return $standardParameter;
    }

}
