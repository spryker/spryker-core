<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;


use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Account;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Analysis;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Customer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Frontend;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Identification;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\User;

class PreAuthorizationRequest extends AbstractRequest
{
    /**
     * @var  Header
     */
    protected $header;

    /**
     * @var Transaction
     */
    protected $transaction;

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
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param Header $header
     */
    public function setHeader(Header $header)
    {
        $this->header = $header;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param Transaction $transaction
     */
    public function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
