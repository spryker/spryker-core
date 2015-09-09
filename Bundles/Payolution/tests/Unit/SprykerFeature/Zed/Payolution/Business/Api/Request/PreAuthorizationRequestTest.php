<?php
namespace Unit\SprykerFeature\Zed\Payolution\Business\Api\Request;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Account;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Address;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Analysis;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Contact;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Customer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Identification;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Name;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Payment;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Presentation;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Security;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\User;
use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class PreAuthorizationRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing abstract classes is somewhat weird and it should be sufficient to
     * test public functionality of an abstract class in a concrete class.
     * Note: It should not be necessary to test this functionality again.
     */
    public function testToArray()
    {
        $preAuthorizationRequest = new PreAuthorizationRequest();
        $preAuthorizationRequest->setHeader($this->getHeaderPartialRequest());
        $preAuthorizationRequest->setTransaction($this->getTransactionPartialRequest());

        $this->assertEquals(
            [
                'SECURITY.SENDER' => '1234567890',
                'TRANSACTION.MODE' => Transaction::MODE_TEST,
                'TRANSACTION.CHANNEL' => '0987654321',
                'USER.LOGIN' => 'john.doe',
                'USER.PWD' => 'test123',
                'IDENTIFICATION.TRANSACTIONID' => '123',
                'IDENTIFICATION.SHOPPERID' => 'customer123',
                'PAYMENT.CODE' => Payment::CODE_PRE_AUTHORIZATION,
                'PRESENTATION.AMOUNT' => 100.00,
                'PRESENTATION.CURRENCY' => 'EUR',
                'PRESENTATION.USAGE' => 'Clock',
                'NAME.FAMILY' => 'Doe',
                'NAME.GIVEN' => 'John',
                'NAME.BIRTHDATE' => '1970-01-01',
                'NAME.SEX' => Name::SEX_MALE,
                'NAME.TITLE' => 'Mr.',
                'ADDRESS.COUNTRY' => 'Germany',
                'ADDRESS.CITY' => 'Berlin',
                'ADDRESS.ZIP' => '10623',
                'ADDRESS.STREET' => 'Straße des 17. Juni 135',
                'CONTACT.EMAIL' => 'john@doe.com',
                'CONTACT.IP' => '127.0.0.1',
                'CONTACT.PHONE' => '030 0815',
                'ACCOUNT.BRAND' => Account::BRAND_INVOICE
            ],
            $preAuthorizationRequest->toArray()
        );
    }

    /**
     * @return Header
     */
    private function getHeaderPartialRequest()
    {
        $header = new Header();
        $header->setSecurity($this->getSecurityPartialRequest());
        return $header;
    }

    /**
     * @return Security
     */
    private function getSecurityPartialRequest()
    {
        $security = new Security();
        $security->setSender('1234567890');
        return $security;
    }

    /**
     * @return Transaction
     */
    private function getTransactionPartialRequest()
    {
        $transaction = new Transaction();
        $transaction->setChannel('0987654321');
        $transaction->setMode(Transaction::MODE_TEST);
        $transaction->setAccount($this->getAccountPartialRequest());
        $transaction->setAnalysis($this->getAnalysisPartialRequest());
        $transaction->setCustomer($this->getCustomerPartialRequest());
        $transaction->setIdentification($this->getIdentificationPartialRequest());
        $transaction->setPayment($this->getPaymentPartialRequest());
        $transaction->setUser($this->getUserPartialRequest());
        return $transaction;
    }

    /**
     * @return Account
     */
    private function getAccountPartialRequest()
    {
        $account = new Account();
        $account->setBrand(Account::BRAND_INVOICE);
        return$account;
    }

    /**
     * @return Analysis
     */
    private function getAnalysisPartialRequest()
    {
        $analysis = new Analysis();
        return $analysis;
    }

    /**
     * @return Customer
     */
    private function getCustomerPartialRequest()
    {
        $customer = new Customer();
        $customer->setAddress($this->getAddressPartialRequest());
        $customer->setName($this->getNamePartialRequest());
        $customer->setContact($this->getContactPartialRequest());
        return $customer;
    }

    /**
     * @return Address
     */
    private function getAddressPartialRequest()
    {
        $address = new Address();
        $address->setCountry('Germany');
        $address->setCity('Berlin');
        $address->setStreet('Straße des 17. Juni 135');
        $address->setZip('10623');
        return $address;
    }

    /**
     * @return Name
     */
    private function getNamePartialRequest()
    {
        $name = new Name();
        $name->setGiven('John');
        $name->setFamily('Doe');
        $name->setSex(Name::SEX_MALE);
        $name->setBirthdate('1970-01-01');
        $name->setTitle('Mr.');
        return $name;
    }

    /**
     * @return Contact
     */
    private function getContactPartialRequest()
    {
        $contact = new Contact();
        $contact->setEmail('john@doe.com');
        $contact->setIp('127.0.0.1');
        $contact->setPhone('030 0815');
        return $contact;
    }

    /**
     * @return Identification
     */
    private function getIdentificationPartialRequest()
    {
        $identification = new Identification();
        $identification->setShopperID('customer123');
        $identification->setTransactionID('123');
        return $identification;
    }

    /**
     * @return Payment
     */
    private function getPaymentPartialRequest()
    {
        $payment = new Payment();
        $payment->setCode(Payment::CODE_PRE_AUTHORIZATION);
        $payment->setPresentation($this->getPresentationPartialRequest());
        return $payment;
    }

    /**
     * @return Presentation
     */
    private function getPresentationPartialRequest()
    {
        $presentation = new Presentation();
        $presentation->setAmount(100.00);
        $presentation->setUsage('Clock');
        $presentation->setCurrency('EUR');
        return $presentation;
    }

    /**
     * @return User
     */
    private function getUserPartialRequest()
    {
        $user = new User();
        $user->setLogin('john.doe');
        $user->setPwd('test123');
        return $user;
    }
}
