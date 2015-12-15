<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Shared\Payolution\PayolutionConfigConstants;

class PayolutionConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getTransactionGatewayUrl()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_GATEWAY_URL);
    }

    /**
     * @return string
     */
    public function getCalculationGatewayUrl()
    {
        return $this->get(PayolutionConfigConstants::CALCULATION_GATEWAY_URL);
    }

    /**
     * @return string
     */
    public function getTransactionSecuritySender()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_SECURITY_SENDER);
    }

    /**
     * @return string
     */
    public function getTransactionUserLogin()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_USER_LOGIN);
    }

    /**
     * @return string
     */
    public function getTransactionUserPassword()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_USER_PASSWORD);
    }

    /**
     * @return string
     */
    public function getCalculationSender()
    {
        return $this->get(PayolutionConfigConstants::CALCULATION_SENDER);
    }

    /**
     * @return string
     */
    public function getCalculationUserLogin()
    {
        return $this->get(PayolutionConfigConstants::CALCULATION_USER_LOGIN);
    }

    /**
     * @return string
     */
    public function getCalculationUserPassword()
    {
        return $this->get(PayolutionConfigConstants::CALCULATION_USER_PASSWORD);
    }

    /**
     * @return string
     */
    public function getTransactionMode()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_MODE);
    }

    /**
     * @return string
     */
    public function getCalculationMode()
    {
        return $this->get(PayolutionConfigConstants::CALCULATION_MODE);
    }

    /**
     * @return string
     */
    public function getTransactionChannelInvoice()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_CHANNEL_INVOICE);
    }

    /**
     * @return string
     */
    public function getTransactionChannelInstallment()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_CHANNEL_INSTALLMENT);
    }

    /**
     * @return string
     */
    public function getTransactionChannelPreCheck()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_CHANNEL_PRE_CHECK);
    }

    /**
     * @return string
     */
    public function getCalculationChannel()
    {
        return $this->get(PayolutionConfigConstants::CALCULATION_CHANNEL);
    }

    /**
     * @return int
     */
    public function getMinOrderGrandTotalInvoice()
    {
        return $this->get(PayolutionConfigConstants::MIN_ORDER_GRAND_TOTAL_INVOICE);
    }

    /**
     * @return int
     */
    public function getMaxOrderGrandTotalInvoice()
    {
        return $this->get(PayolutionConfigConstants::MAX_ORDER_GRAND_TOTAL_INVOICE);
    }

    /**
     * @return int
     */
    public function getMinOrderGrandTotalInstallment()
    {
        return $this->get(PayolutionConfigConstants::MIN_ORDER_GRAND_TOTAL_INSTALLMENT);
    }

    /**
     * @return int
     */
    public function getMaxOrderGrandTotalInstallment()
    {
        return $this->get(PayolutionConfigConstants::MAX_ORDER_GRAND_TOTAL_INSTALLMENT);
    }

    /**
     * @return string
     */
    public function getPayolutionBccEmail()
    {
        return $this->get(PayolutionConfigConstants::PAYOLUTION_BCC_EMAIL);
    }

    /**
     * @return string
     */
    public function getEmailFromName()
    {
        return $this->get(PayolutionConfigConstants::EMAIL_FROM_NAME);
    }

    /**
     * @return string
     */
    public function getEmailFromAddress()
    {
        return $this->get(PayolutionConfigConstants::EMAIL_FROM_ADDRESS);
    }

    /**
     * @return string
     */
    public function getEmailTemplateName()
    {
        return $this->get(PayolutionConfigConstants::EMAIL_TEMPLATE_NAME);
    }

    /**
     * @return string
     */
    public function getEmailSubject()
    {
        return $this->get(PayolutionConfigConstants::EMAIL_SUBJECT);
    }

    /**
     * @return string
     */
    public function getWebshopUrl()
    {
        return $this->get(PayolutionConfigConstants::WEBSHOP_URL);
    }

}
