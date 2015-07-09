<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone;

use Generated\Shared\Transfer\StandardParameterTransfer;
use SprykerFeature\Shared\Payone\PayoneConfigConstants;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerEngine\Shared\Kernel\Store;
use Generated\Shared\Payone\StandardParameterInterface;

class PayoneConfig extends AbstractBundleConfig
{

    /**
     * @return StandardParameterInterface
     */
    public function getRequestStandardParameter()
    {
        $settings = $this->get(PayoneConfigConstants::PAYONE);
        $standardParameter = new StandardParameterTransfer();

        $standardParameter->setEncoding($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameter->setMid($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_MID]);
        $standardParameter->setAid($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_AID]);
        $standardParameter->setPortalId($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameter->setKey($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_KEY]);
        $standardParameter->setPaymentGatewayUrl($settings[PayoneConfigConstants::PAYONE_PAYMENT_GATEWAY_URL]);

        $standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

        $standardParameter->setRedirectSuccessUrl($settings[PayoneConfigConstants::PAYONE_REDIRECT_SUCCESS_URL]);
        $standardParameter->setRedirectBackUrl($settings[PayoneConfigConstants::PAYONE_REDIRECT_BACK_URL]);
        $standardParameter->setRedirectErrorUrl($settings[PayoneConfigConstants::PAYONE_REDIRECT_ERROR_URL]);

        return $standardParameter;
    }

}
