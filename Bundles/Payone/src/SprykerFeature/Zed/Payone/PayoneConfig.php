<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone;

use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use SprykerFeature\Shared\Payone\PayoneConstants;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\Application\ApplicationConstants;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Orm\Zed\Sales\Persistence\SpySalesOrder;

class PayoneConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getMode()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_MODE];
    }

    /**
     * @return string
     */
    public function getEmptySequenceNumber()
    {
        $settings = $this->get(PayoneConstants::PAYONE);

        return $settings[PayoneConstants::PAYONE_EMPTY_SEQUENCE_NUMBER];
    }

    /**
     * @return PayoneStandardParameterTransfer
     */
    public function getRequestStandardParameter()
    {
        $settings = $this->get(PayoneConstants::PAYONE);
        $standardParameter = new PayoneStandardParameterTransfer();

        $standardParameter->setEncoding($settings[PayoneConstants::PAYONE_CREDENTIALS_ENCODING]);
        $standardParameter->setMid($settings[PayoneConstants::PAYONE_CREDENTIALS_MID]);
        $standardParameter->setAid($settings[PayoneConstants::PAYONE_CREDENTIALS_AID]);
        $standardParameter->setPortalId($settings[PayoneConstants::PAYONE_CREDENTIALS_PORTAL_ID]);
        $standardParameter->setKey($settings[PayoneConstants::PAYONE_CREDENTIALS_KEY]);
        $standardParameter->setPaymentGatewayUrl($settings[PayoneConstants::PAYONE_PAYMENT_GATEWAY_URL]);

        $standardParameter->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $standardParameter->setLanguage(Store::getInstance()->getCurrentLanguage());

        $standardParameter->setRedirectSuccessUrl($this->getYvesBaseUrl() . $settings[PayoneConstants::PAYONE_REDIRECT_SUCCESS_URL]);
        $standardParameter->setRedirectBackUrl($this->getYvesBaseUrl() . $settings[PayoneConstants::PAYONE_REDIRECT_BACK_URL]);
        $standardParameter->setRedirectErrorUrl($this->getYvesBaseUrl() . $settings[PayoneConstants::PAYONE_REDIRECT_ERROR_URL]);

        return $standardParameter;
    }

    /**
     * @param PayonePaymentTransfer $paymentTransfer
     * @param SpySalesOrder $orderEntity
     *
     * @return string
     */
    public function generatePayoneReference(PayonePaymentTransfer $paymentTransfer, SpySalesOrder $orderEntity)
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
        return $this->get(ApplicationConstants::HOST_YVES);
    }

}
