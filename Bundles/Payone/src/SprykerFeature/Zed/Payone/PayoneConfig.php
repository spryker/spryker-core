<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone;

use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Payone\PayoneStandardParameterInterface;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerFeature\Shared\Payone\PayoneConfigConstants;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class PayoneConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getMode()
    {
        $settings = $this->get(PayoneConfigConstants::PAYONE);

        return $settings[PayoneConfigConstants::PAYONE_MODE];
    }

    /**
     * @return string
     */
    public function getEmptySequenceNumber()
    {
        $settings = $this->get(PayoneConfigConstants::PAYONE);

        return $settings[PayoneConfigConstants::PAYONE_EMPTY_SEQUENCE_NUMBER];
    }

    /**
     * @return PayoneStandardParameterInterface
     */
    public function getRequestStandardParameter()
    {
        $settings = $this->get(PayoneConfigConstants::PAYONE);
        $standardParameter = new PayoneStandardParameterTransfer();

        $standardParameter->setEncoding($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameter->setMid($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_MID]);
        $standardParameter->setAid($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_AID]);
        $standardParameter->setPortalId($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameter->setKey($settings[PayoneConfigConstants::PAYONE_CREDENTIALS_KEY]);
        $standardParameter->setPaymentGatewayUrl($settings[PayoneConfigConstants::PAYONE_PAYMENT_GATEWAY_URL]);

        $standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

        $standardParameter->setRedirectSuccessUrl($this->getYvesBaseUrl() . $settings[PayoneConfigConstants::PAYONE_REDIRECT_SUCCESS_URL]);
        $standardParameter->setRedirectBackUrl($this->getYvesBaseUrl() . $settings[PayoneConfigConstants::PAYONE_REDIRECT_BACK_URL]);
        $standardParameter->setRedirectErrorUrl($this->getYvesBaseUrl() . $settings[PayoneConfigConstants::PAYONE_REDIRECT_ERROR_URL]);

        return $standardParameter;
    }

    /**
     * @param PayonePaymentInterface $paymentTransfer
     * @param SpySalesOrder $orderEntity
     *
     * @return string
     */
    public function generatePayoneReference(PayonePaymentInterface $paymentTransfer, SpySalesOrder $orderEntity)
    {
        return $orderEntity->getOrderReference();
    }

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     *
     * @return string
     */
    public function getNarrativeText(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        return $orderEntity->getOrderReference();
    }

    /**
     * @return string
     */
    protected function getYvesBaseUrl()
    {
        return $this->get(SystemConfig::HOST_YVES);
    }

}
