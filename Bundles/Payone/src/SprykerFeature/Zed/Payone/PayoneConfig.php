<?php

/*
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Zed\Payone;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerFeature\Shared\Payone\PayoneConfigConstants;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\System\SystemConfig;
use \SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Payone\Dependency\Transfer\StandardParameterInterface;


class PayoneConfig extends AbstractBundleConfig
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
     * @return StandardParameterInterface
     */
    public function getRequestStandardParameter()
    {
        $credentials = $this->get(PayoneConfigConstants::PAYONE_CREDENTIALS);
        $standardParameter = new PayoneStandardParameterTransfer();

        $standardParameter->setEncoding($credentials[PayoneConfigConstants::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameter->setMid($credentials[PayoneConfigConstants::PAYONE_CREDENTIALS_MID]);
        $standardParameter->setAid($credentials[PayoneConfigConstants::PAYONE_CREDENTIALS_AID]);
        $standardParameter->setPortalId($credentials[PayoneConfigConstants::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameter->setKey($credentials[PayoneConfigConstants::PAYONE_CREDENTIALS_KEY]);
        $standardParameter->setPaymentGatewayUrl($credentials[PayoneConfigConstants::PAYONE_PAYMENT_GATEWAY_URL]);

        $standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

        $standardParameter->setRedirectSuccessUrl($this->get(SystemConfig::HOST_YVES) . '/' . $this->getRedirectSuccessUrl());
        $standardParameter->setRedirectBackUrl($this->get(SystemConfig::HOST_YVES) . '/' . $this->getRedirectBackUrl());
        $standardParameter->setRedirectErrorUrl($this->get(SystemConfig::HOST_YVES) . '/' . $this->getRedirectErrorUrl());

        return $standardParameter;
    }

}
