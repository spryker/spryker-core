<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;


abstract class AbstractRequest extends AbstractRequestExporter
{

    /**
     * @var  Header
     */
    protected $header;

    /**
     * @var Transaction
     */
    protected $transaction;

    public function __construct()
    {
        $this->setHeader(new Header());
        $this->setTransaction(new Transaction());
    }

    /**
     * @return Header
     */
    private function getHeader()
    {
        return $this->header;
    }

    /**
     * @param Header $header
     */
    private function setHeader(Header $header)
    {
        $this->header = $header;
    }

    /**
     * @return Transaction
     */
    private function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param Transaction $transaction
     */
    private function setTransaction(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setTransactionMode($mode)
    {
        $this->getTransaction()->setMode($mode);
        return $this;
    }

    /**
     * @param string $channel
     *
     * @return $this
     */
    public function setTransactionChannel($channel)
    {
        $this->getTransaction()->setChannel($channel);
        return $this;
    }


    /**
     * @param string $sender
     *
     * @return $this
     */
    public function setSecuritySender($sender)
    {
        $this->getHeader()->getSecurity()->setSender($sender);
        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setUserPassword($password)
    {
        $this->getTransaction()->getUser()->setPwd($password);
        return $this;
    }

    /**
     * @param string $login
     *
     * @return $this
     */
    public function setUserLogin($login)
    {
        $this->getTransaction()->getUser()->setLogin($login);
        return $this;
    }

    /**
     * @param string $shopperId
     *
     * @return $this
     */
    public function setIdentificationShopperId($shopperId)
    {
        $this->getTransaction()->getIdentification()->setShopperID($shopperId);
        return $this;
    }

    /**
     * @param string $transactionId
     *
     * @return $this
     */
    public function setIdentificationTransactionId($transactionId)
    {
        $this->getTransaction()->getIdentification()->setTransactionID($transactionId);
        return $this;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setPaymentCode($code)
    {
        $this->getTransaction()->getPayment()->setCode($code);
        return $this;
    }

    /**
     * @param string $amount
     *
     * @return $this
     */
    public function setPresentationAmount($amount)
    {
        $this->getTransaction()->getPayment()->getPresentation()
            ->setAmount($amount);
        return $this;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setPresentationCurrency($currency)
    {
        $this->getTransaction()->getPayment()->getPresentation()
            ->setCurrency($currency);
        return $this;
    }

    /**
     * @param string $usage
     *
     * @return $this
     */
    public function setPresentationUsage($usage)
    {
        $this->getTransaction()->getPayment()->getPresentation()->setUsage($usage);
        return $this;
    }

    /**
     * @param string $countryIso2Code
     *
     * @return $this
     */
    public function setAdressCountryIso2Code($countryIso2Code)
    {
        $this->getTransaction()->getCustomer()->getAddress()->setCountry($countryIso2Code);
        return $this;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setAdressCity($city)
    {
        $this->getTransaction()->getCustomer()->getAddress()->setCity($city);
        return $this;
    }

    /**
     * @param string $street
     *
     * @return $this
     */
    public function setAdressStreet($street)
    {
        $this->getTransaction()->getCustomer()->getAddress()->setStreet($street);
        return $this;
    }

    /**
     * @param string $zip
     *
     * @return $this
     */
    public function setAdressZip($zip)
    {
        $this->getTransaction()->getCustomer()->getAddress()->setZip($zip);
        return $this;
    }

    /**
     * @param string $family
     *
     * @return $this
     */
    public function setNameFamily($family)
    {
        $this->getTransaction()->getCustomer()->getName()->setFamily($family);
        return $this;
    }

    /**
     * @param string $given
     *
     * @return $this
     */
    public function setNameGiven($given)
    {
        $this->getTransaction()->getCustomer()->getName()->setGiven($given);
        return $this;
    }

    /**
     * @param string $birthdate
     *
     * @return $this
     */
    public function setNameBirthdate($birthdate)
    {
        $this->getTransaction()->getCustomer()->getName()->setBirthdate($birthdate);
        return $this;
    }

    /**
     * @param string $sex
     *
     * @return $this
     */
    public function setNameSex($sex)
    {
        $this->getTransaction()->getCustomer()->getName()->setSex($sex);
        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setNameTitle($title)
    {
        $this->getTransaction()->getCustomer()->getName()->setTitle($title);
        return $this;
    }

    /**
     * @param string $brand
     *
     * @return $this
     */
    public function setAccountBrand($brand)
    {
        $this->getTransaction()->getAccount()->setBrand($brand);
        return $this;
    }

    /**
     * @param string $ip
     *
     * @return $this
     */
    public function setContactIp($ip)
    {
        $this->getTransaction()->getCustomer()->getContact()->setIp($ip);
        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setContactEmail($email)
    {
        $this->getTransaction()->getCustomer()->getContact()->setEmail($email);
        return $this;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setContactPhone($phone)
    {
        $this->getTransaction()->getCustomer()->getContact()->setPhone($phone);
        return $this;
    }

    /**
     * @param string $mobile
     *
     * @return $this
     */
    public function setContactMobile($mobile)
    {
        $this->getTransaction()->getCustomer()->getContact()->setMobile($mobile);
        return $this;
    }

    public function getPaymentCode()
    {
        return $this->getTransaction()->getPayment()->getCode();
    }

    public function getPresentationAmount()
    {
        return $this->getTransaction()->getPayment()->getPresentation()->getAmount();
    }

    public function getPresentationCurrency()
    {
        return $this->getTransaction()->getPayment()->getPresentation()->getCurrency();
    }

    public function getIdentificationTransactionId()
    {
        return $this->getTransaction()->getIdentification()->getTransactionID();
    }

    public function getIdentifactionReferenceId()
    {
        return $this->getTransaction()->getIdentification()->getReferenceID();
    }
}


