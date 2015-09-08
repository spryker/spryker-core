<?php
namespace Unit\SprykerFeature\Zed\Payolution\Business\Api\Request;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Address;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Contact;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Customer;
use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class PreAuthorizationRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $preAuthorizationRequest = new PreAuthorizationRequest();
        $preAuthorizationRequest->setCustomer($this->getCustomerPartialRequest());

        $this->assertEquals(
            [
                'CONTACT.EMAIL' => 'john@doe.com',
                'CONTACT.IP' => '127.0.0.1',
                'CONTACT.PHONE' => '030 0815',
                'ADDRESS.COUNTRY' => 'Germany',
                'ADDRESS.CITY' => 'Berlin',
                'ADDRESS.ZIP' => '10623',
                'ADDRESS.STREET' => 'Straße des 17. Juni 135'
            ],
            $preAuthorizationRequest->toArray()
        );
    }

    /**
     * @return Customer
     */
    private function getCustomerPartialRequest()
    {
        $customer = new Customer();
        $customer->setContact($this->getContactPartialRequest());
        $customer->setAddress($this->getAddressPartialRequest());
        return $customer;
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
}
