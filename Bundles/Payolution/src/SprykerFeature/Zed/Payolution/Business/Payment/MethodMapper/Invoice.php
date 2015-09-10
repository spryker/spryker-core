<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Account;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Address;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Analysis;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Contact;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Customer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Identification;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Name;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Presentation;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\User;
use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class Invoice extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return MethodMapperInterface::INVOICE;
    }

    /**
     * @param SpyPaymentPayolution $payment
     *
     * @return PreAuthorizationRequest
     */
    public function mapToPreAuthorization(SpyPaymentPayolution $paymentEntity)
    {
        $orderEntity = $paymentEntity->getSpySalesOrder();

        $request = new PreAuthorizationRequest();
        $request->setHeader($this->getHeaderPartialRequest());

        // @todo  move to own method
        $presentation = new Presentation();
        $presentation->setAmount($orderEntity->getGrandTotal());
        $presentation->setCurrency(Store::getInstance()->getCurrencyIsoCode());
        $presentation->setUsage($orderEntity->getIdSalesOrder());

        $payment = $this->getPaymentPartialRequest($presentation, Payment::CODE_PRE_AUTHORIZATION);
        $user = $this->getUserPartialRequest();
        $identification = $this->getIdentificationPartialRequest($orderEntity);

        $address = $this->getAddressPartialRequest($orderEntity->getBillingAddress());
        $name = $this->getNamePartialRequest($orderEntity->getCustomer());
        $contact = $this->getContactPartialRequest(
            $orderEntity->getCustomer(),
            $paymentEntity->getClientIp()
        );

        $customer = $this->getCustomerPartialRequest($address, $name, $contact);

        $account = $this->getAccountPartialRequest();

        $analysis = new Analysis();

        $transaction = $this->getTransactionPartialRequest(
            $user,
            $identification,
            $payment,
            $customer,
            $account,
            $analysis
        );

        $request->setTransaction($transaction);

        return $request;
    }

    /**
     * @param SpySalesOrderAddress $orderAddress
     *
     * @return Address
     */
    private function getAddressPartialRequest(SpySalesOrderAddress $orderAddress)
    {
        $address = new Address();
        $address->setCountry($orderAddress->getCountry()->getIso2Code());
        $address->setCity($orderAddress->getCity());

        // @todo which part of address is needed?
        $address->setStreet($orderAddress->getAddress1());
        $address->setZip($orderAddress->getZipCode());

        return $address;
    }

    /**
     * @param SpyCustomer $customer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return Name
     */
    private function getNamePartialRequest(SpyCustomer $customer)
    {
        $name = new Name();
        $name->setFamily($customer->getLastName());
        $name->setGiven($customer->getFirstName());

        // @todo find out howto generate the date format
        $name->setBirthdate($customer->getDateOfBirth('Y-m-d'));

        $genderMap = [SpyCustomerTableMap::COL_GENDER_MALE => "M", SpyCustomerTableMap::COL_GENDER_FEMALE => "F"];

        $name->setSex($genderMap[$customer->getGender()]);
        $name->setTitle($customer->getSalutation());
        return $name;
    }

    /**
     * @param SpyCustomer $customer
     * @param $ip
     *
     * @return Contact
     */
    private function getContactPartialRequest(SpyCustomer $customer, $ip)
    {
        $contact = new Contact();
        $contact->setEmail($customer->getEmail());
        $contact->setIp($ip);

        return $contact;
    }

    /**
     * @param Address $address
     * @param Name $name
     * @param Contact $contact
     *
     * @return Customer
     */
    private function getCustomerPartialRequest(Address $address, Name $name, Contact $contact)
    {
        $customer = new Customer();
        $customer->setAddress($address);
        $customer->setName($name);
        $customer->setContact($contact);

        return $customer;
    }

    private function getAccountPartialRequest()
    {
        $account = new Account();
        $account->setBrand(Account::BRAND_INVOICE);

        return $account;
    }

    /**
     * @param User $user
     * @param Identification $identification
     * @param Payment $payment
     * @param Customer $customer
     * @param Account $account
     * @param Analysis $analysis
     *
     * @return Transaction
     */
    private function getTransactionPartialRequest(
        User $user,
        Identification $identification,
        Payment $payment,
        Customer $customer,
        Account $account,
        Analysis $analysis
    ) {
        $transaction = new Transaction();
        $transaction->setUser($user);
        $transaction->setIdentification($identification);
        $transaction->setPayment($payment);
        $transaction->setCustomer($customer);
        $transaction->setAccount($account);
        $transaction->setAnalysis($analysis);
        $transaction->setChannel($this->getConfig()->getChannelInvoice());
        $transaction->setMode($this->getConfig()->getTransactionMode());

        return $transaction;
    }

    /**
     * @param Presentation $presentation
     * @param $paymentCode
     *
     * @return Payment
     */
    private function getPaymentPartialRequest(Presentation $presentation, $paymentCode)
    {
        $payment = new Payment();
        $payment->setCode($paymentCode);
        $payment->setPresentation($presentation);

        return $payment;
    }

    /**
     * @param SpySalesOrder $orderEntity
     *
     * @return Identification
     */
    protected function getIdentificationPartialRequest(SpySalesOrder $orderEntity)
    {
        $identification = new Identification();
        $identification->setShopperID($orderEntity->getCustomer()->getCustomerReference());

        //@todo replace transID generation
        $identification->setTransactionID(uniqid('trans_'));

        return $identification;
    }

}
