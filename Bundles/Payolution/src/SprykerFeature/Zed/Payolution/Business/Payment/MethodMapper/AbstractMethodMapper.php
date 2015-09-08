<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\User;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\PayolutionConfig;

abstract class AbstractMethodMapper implements MethodMapperInterface
{

    /**
     * @var PayolutionConfig
     */
    private $config;

    public function __construct(PayolutionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return PayolutionConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Header
     */
    protected function getHeaderPartialRequest()
    {
        $header = new Header();
        $header->setSecurity($this->getConfig()->getSecuritySender());
        return $header;
    }

    /**
     * @return Transaction
     */
    protected function getTransactionPartialRequest()
    {
        $transaction = new Transaction();
        $transaction->setMode($this->getConfig()->getTransactionMode());
        $transaction->setChannel($this->getChannel());
        $transaction->setUser($this->getUserPartialRequest());
        // $transaction->setIdentification();
        // $transaction->setPayment();
        // $transaction->setCustomer();
        // $transaction->setAccount();
        // $transaction->setFrontend();
        // $transaction->setAnalysis();
        return $transaction;
    }

    /**
     * @return string
     */
    abstract protected function getChannel();

    /**
     * @return User
     */
    protected function getUserPartialRequest()
    {
        $user = new User();
        $user->setLogin($this->getConfig()->getUserLogin());
        $user->setPassword($this->getConfig()->getUserPassword());
        return $user;
    }

}
