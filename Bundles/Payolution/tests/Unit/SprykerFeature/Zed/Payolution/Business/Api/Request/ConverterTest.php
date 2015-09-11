<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Payolution\Business\Api\Request;

use Generated\Shared\Transfer\PayolutionRequestTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Converter;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    public function testToArray()
    {
        $exporter = new Converter();
        $requestArray = $exporter->toArray($this->getTestPaymentTransfer());
        $this->assertEquals(
            [
                'SECURITY.SENDER' => '1234567890',
                'TRANSACTION.MODE' => Constants::TRANSACTION_MODE_TEST,
                'TRANSACTION.CHANNEL' => '0987654321',
                'USER.LOGIN' => 'john.doe',
                'USER.PWD' => 'test123',
                'IDENTIFICATION.TRANSACTIONID' => '123',
                'IDENTIFICATION.SHOPPERID' => 'customer123',
                'PAYMENT.CODE' => Constants::PAYMENT_CODE_PRE_AUTHORIZATION,
                'PRESENTATION.AMOUNT' => 100.00,
                'PRESENTATION.CURRENCY' => 'EUR',
                'PRESENTATION.USAGE' => 'Clock',
                'NAME.FAMILY' => 'Doe',
                'NAME.GIVEN' => 'John',
                'NAME.BIRTHDATE' => '1970-01-01',
                'NAME.SEX' => Constants::SEX_MALE,
                'NAME.TITLE' => 'Mr',
                'ADDRESS.COUNTRY' => 'DE',
                'ADDRESS.CITY' => 'Berlin',
                'ADDRESS.ZIP' => '10623',
                'ADDRESS.STREET' => 'Straße des 17. Juni 135',
                'CONTACT.EMAIL' => 'john@doe.com',
                'CONTACT.IP' => '127.0.0.1',
                'CONTACT.PHONE' => '030 0815',
                'ACCOUNT.BRAND' => Constants::ACCOUNT_BRAND_INVOICE,
            ],
            $requestArray
        );
    }

    /**
     * @return PayolutionRequestTransfer
     */
    private function getTestPaymentTransfer()
    {
        return (new PayolutionRequestTransfer())
            ->setSecuritySender('1234567890')
            ->setTransactionChannel('0987654321')
            ->setTransactionMode(Constants::TRANSACTION_MODE_TEST)
            ->setUserLogin('john.doe')
            ->setUserPwd('test123')
            ->setPaymentCode(Constants::PAYMENT_CODE_PRE_AUTHORIZATION)
            ->setPresentationAmount(100.00)
            ->setPresentationCurrency('EUR')
            ->setPresentationUsage('Clock')
            ->setNameFamily('Doe')
            ->setNameGiven('John')
            ->setNameSex(Constants::SEX_MALE)
            ->setNameTitle('Mr')
            ->setNameBirthdate('1970-01-01')
            ->setAddressCity('Berlin')
            ->setAddressCountry('DE')
            ->setAddressStreet('Straße des 17. Juni 135')
            ->setAddressZip('10623')
            ->setContactEmail('john@doe.com')
            ->setContactIp('127.0.0.1')
            ->setContactPhone('030 0815')
            ->setIdentificationTransactionid('123')
            ->setIdentificationShopperid('customer123')
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE);
    }

}
