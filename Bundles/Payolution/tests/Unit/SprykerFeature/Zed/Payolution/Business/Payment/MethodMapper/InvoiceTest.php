<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Unit\SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\PayolutionRequestTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Shared\Payolution\PayolutionApiConstants;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper\Invoice;
use SprykerFeature\Zed\Payolution\PayolutionConfig;
use Orm\Zed\Payolution\Persistence\Map\SpyPaymentPayolutionTableMap;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;

class InvoiceTest extends Test
{

    public function testMapToPreCheck()
    {

        $checkoutRequestTransfer = $this->getCheckoutRequestTransfer();
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $requestTransfer = $methodMapper->mapToPreCheck($checkoutRequestTransfer);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(PayolutionApiConstants::PAYMENT_CODE_PRE_CHECK, $requestTransfer->getPaymentCode());
        $this->assertSame('Straße des 17. Juni 135', $requestTransfer->getAddressStreet());

        $criteria = $requestTransfer->getAnalysisCriteria();
        $this->assertCount(2, $criteria);
        $this->assertSame($criteria[0]->getName(), Constants::CRITERION_PRE_CHECK);
        $this->assertSame($criteria[0]->getValue(), 'TRUE');
    }

    /**
     * @return CheckoutRequestTransfer
     */
    private function getCheckoutRequestTransfer()
    {
        $totalsTransfer = new TotalsTransfer();
        $totalsTransfer
            ->setGrandTotal(10000)
            ->setGrandTotalWithDiscounts(10000)
            ->setSubtotal(10000);

        $cartTransfer = new CartTransfer();
        $cartTransfer->setTotals($totalsTransfer);

        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setCity('Berlin')
            ->setIso2Code('de')
            ->setAddress1('Straße des 17. Juni')
            ->setAddress2('135')
            ->setZipCode('10623');

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer
            ->setGender('Male')
            ->setDateOfBirth('1970-01-01')
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionApiConstants::BRAND_INVOICE)
            ->setAddress($addressTransfer);

        $checkoutRequestTransfer = new CheckoutRequestTransfer();
        $checkoutRequestTransfer
            ->setIdUser(null)
            ->setPaymentMethod('invoice')
            ->setCart($cartTransfer)
            ->setPayolutionPayment($paymentTransfer);

        return $checkoutRequestTransfer;
    }

    public function testMapToPreAuthorization()
    {
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToPreAuthorization($paymentEntityMock);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame($paymentEntityMock->getEmail(), $requestTransfer->getContactEmail());
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_PRE_AUTHORIZATION, $requestTransfer->getPaymentCode());
        $this->assertCount(1, $requestTransfer->getAnalysisCriteria());
    }

    public function testMapToReAuthorization()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToReAuthorization($paymentEntityMock, $uniqueId);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_RE_AUTHORIZATION, $requestTransfer->getPaymentCode());
        $this->assertSame($uniqueId, $requestTransfer->getIdentificationReferenceid());
        $this->testForAbsentCustomerData($requestTransfer);
    }

    public function testMapToReversal()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToReversal($paymentEntityMock, $uniqueId);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_REVERSAL, $requestTransfer->getPaymentCode());
        $this->assertSame($uniqueId, $requestTransfer->getIdentificationReferenceid());
        $this->testForAbsentCustomerData($requestTransfer);
    }

    public function testMapToCapture()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToCapture($paymentEntityMock, $uniqueId);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_CAPTURE, $requestTransfer->getPaymentCode());
        $this->assertSame($uniqueId, $requestTransfer->getIdentificationReferenceid());
        $this->testForAbsentCustomerData($requestTransfer);
    }

    public function testMapToRefund()
    {
        $uniqueId = uniqid('test_');
        $methodMapper = new Invoice($this->getBundleConfigMock());
        $paymentEntityMock = $this->getPaymentEntityMock();
        $requestTransfer = $methodMapper->mapToRefund($paymentEntityMock, $uniqueId);

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionRequestTransfer', $requestTransfer);
        $this->assertSame(PayolutionApiConstants::BRAND_INVOICE, $requestTransfer->getAccountBrand());
        $this->assertSame(Constants::PAYMENT_CODE_REFUND, $requestTransfer->getPaymentCode());
        $this->assertSame($uniqueId, $requestTransfer->getIdentificationReferenceid());
        $this->testForAbsentCustomerData($requestTransfer);
    }

    /**
     * For some requests (e.g. re-authorization) we don't expect customer data
     *
     * @param PayolutionRequestTransfer $requestTransfer
     */
    private function testForAbsentCustomerData(PayolutionRequestTransfer $requestTransfer)
    {
        $this->assertNull($requestTransfer->getNameGiven());
        $this->assertNull($requestTransfer->getNameFamily());
        $this->assertNull($requestTransfer->getNameTitle());
        $this->assertNull($requestTransfer->getNameSex());
        $this->assertNull($requestTransfer->getNameBirthdate());
        $this->assertNull($requestTransfer->getAddressCity());
        $this->assertNull($requestTransfer->getAddressCountry());
        $this->assertNull($requestTransfer->getAddressStreet());
        $this->assertNull($requestTransfer->getAddressZip());
        $this->assertNull($requestTransfer->getContactEmail());
        $this->assertNull($requestTransfer->getContactIp());
        $this->assertNull($requestTransfer->getIdentificationShopperid());
    }

    /**
     * @return PayolutionConfig
     */
    private function getBundleConfigMock()
    {
        return $this->getMock(
            'SprykerFeature\Zed\Payolution\PayolutionConfig',
            $methods = [],
            $arguments = [],
            $mockClassName = '',
            $callOriginalConstructor = false
        );
    }

    /**
     * @return SpyPaymentPayolution
     */
    private function getPaymentEntityMock()
    {
        $orderEntityMock = $this->getMock(
            'Orm\Zed\Sales\Persistence\SpySalesOrder',
            $methods = []
        );

        /** @var SpyPaymentPayolution|\PHPUnit_Framework_MockObject_MockObject $paymentEntityMock */
        $paymentEntityMock = $this->getMock(
            'Orm\Zed\Payolution\Persistence\SpyPaymentPayolution',
            $methods = [
                'getSpySalesOrder',
            ]
        );
        $paymentEntityMock
            ->expects($this->any())
            ->method('getSpySalesOrder')
            ->will($this->returnValue($orderEntityMock));

        $paymentEntityMock
            ->setIdPaymentPayolution(1)
            ->setClientIp('127.0.0.1')
            ->setAccountBrand(PayolutionApiConstants::BRAND_INVOICE)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setSalutation('Mr')
            ->setDateOfBirth('1970-01-01')
            ->setCountryIso2Code('de')
            ->setCity('Berlin')
            ->setStreet('Straße des 17. Juni 135')
            ->setZipCode('10623')
            ->setGender(SpyPaymentPayolutionTableMap::COL_GENDER_FEMALE);

        return $paymentEntityMock;
    }

}
