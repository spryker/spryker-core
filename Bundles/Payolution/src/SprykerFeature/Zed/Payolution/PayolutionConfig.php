<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Payolution\PayolutionConfigConstants;

class PayolutionConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getGatewayUrl()
    {
        return $this->get(PayolutionConfigConstants::GATEWAY_URL);
    }

    /**
     * @return string
     */
    public function getSecuritySender()
    {
        return $this->get(PayolutionConfigConstants::SECURITY_SENDER);
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
    public function getTransactionChannelSync()
    {
        return $this->get(PayolutionConfigConstants::TRANSACTION_CHANNEL_SYNC);
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
    public function getUserLogin()
    {
        return $this->get(PayolutionConfigConstants::USER_LOGIN);
    }

    /**
     * @return string
     */
    public function getUserPassword()
    {
        return $this->get(PayolutionConfigConstants::USER_PASSWORD);
    }

}
