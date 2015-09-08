<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;

use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Account;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Analysis;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Customer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Frontend;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Identification;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\User;

class Transaction extends AbstractRequest
{

    const MODE_TEST = 'CONNECTOR_TEST';
    const MODE_LIVE = 'LIVE';

    /**
     * @var  string
     */
    protected $mode;

    /**
     * @var  string
     */
    protected $channel;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Identification
     */
    protected $identification;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var Frontend
     */
    protected $frontend;

    /**
     * @var Analysis
     */
    protected $analysis;

    /**
     * @return string
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Identification
     */
    public function getIdentification()
    {
        return $this->identification;
    }

    /**
     * @param Identification $identification
     */
    public function setIdentification(Identification $identification)
    {
        $this->identification = $identification;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param Account $account
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    /**
     * @return Frontend
     */
    public function getFrontend()
    {
        return $this->frontend;
    }

    /**
     * @param Frontend $frontend
     */
    public function setFrontend(Frontend $frontend)
    {
        $this->frontend = $frontend;
    }

    /**
     * @return Analysis
     */
    public function getAnalysis()
    {
        return $this->analysis;
    }

    /**
     * @param Analysis $analysis
     */
    public function setAnalysis(Analysis $analysis)
    {
        $this->analysis = $analysis;
    }

}
