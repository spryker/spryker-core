<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Functional\SprykerFeature\Zed\Payolution\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress;

class PayolutionFacadeTest extends Test
{

    /**
     * @var SpySalesOrder
     */
    private $orderEntity;

    /**
     * Test the saveOrderPayment() method of PayolutionFacade
     */
    public function testSaveOrderPayment()
    {
        $this->setTestData();

        $paymentTransfer = new PayolutionPaymentTransfer();
        $paymentTransfer->setFkSalesOrder($this->orderEntity->getIdSalesOrder());
        $paymentTransfer->setPaymentCode(Constants::PAYMENT_CODE_PRE_AUTHORIZATION);
        $paymentTransfer->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE);
        $paymentTransfer->setClientIp('127.0.0.1');

        // PayolutionCheckoutConnector-HydrateOrderPlugin emulation
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setIdSalesOrder($this->orderEntity->getIdSalesOrder());
        $orderTransfer->setPayolutionPayment($paymentTransfer);

        // PayolutionCheckoutConnector-SaveOrderPlugin emulation
        $facade = $this->getLocator()->payolution()->facade();
        $facade->saveOrderPayment($orderTransfer);

        $paymentEntity = $this->orderEntity->getSpyPaymentPayolutions()->getFirst();

        $this->assertInstanceOf('SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution', $paymentEntity);
        $this->assertEquals(Constants::PAYMENT_CODE_PRE_AUTHORIZATION, $paymentEntity->getPaymentCode());
        $this->assertEquals(Constants::ACCOUNT_BRAND_INVOICE, $paymentEntity->getAccountBrand());
        $this->assertEquals('127.0.0.1', $paymentEntity->getClientIp());
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function testPreAuthorizePayment()
    {
        $this->setTestData();

        $paymentEntity = (new SpyPaymentPayolution())
            ->setFkSalesOrder($this->orderEntity->getIdSalesOrder())
            ->setPaymentCode(Constants::PAYMENT_CODE_PRE_AUTHORIZATION)
            ->setAccountBrand(Constants::ACCOUNT_BRAND_INVOICE)
            ->setClientIp('127.0.0.1');
        $paymentEntity->save();

        $facade = $this->getLocator()->payolution()->facade();
        $response = $facade->preAuthorizePayment($paymentEntity->getIdPaymentPayolution());

        $this->assertInstanceOf('Generated\Shared\Transfer\PayolutionResponseTransfer', $response);

        $this->assertEquals(0, $response->getProcessingRiskScore());
        $this->assertEquals('unknown', $response->getP3Validation());
        $this->assertEquals($this->orderEntity->getFirstName(), $response->getNameGiven());
        $this->assertEquals('shopperID', $response->getIdentificationShopperid());
        $this->assertEquals('expected clearing', $response->getClearingDescriptor());
        $this->assertEquals('expected connector xid1', $response->getProcessingConnectordetailConnectortxid1());
        $this->assertEquals('expected channel', $response->getTransactionChannel());
        $this->assertEquals('expected reasonCode', $response->getProcessingReason());
        $this->assertEquals($this->orderEntity->getBillingAddress()->getCity(), $response->getAddressCity());
        $this->assertEquals(0, $response->getFrontendRequestCancelled());
        $this->assertEquals('expecetd proc code', $response->getProcessingCode());
        $this->assertEquals('expec proc reason', $response->getProcessingReason());
        $this->assertEquals('exp front  mode', $response->getFrontendMode());
        $this->assertEquals('exp clearing fx', $response->setClearingFxsource());
        $this->assertEquals('exp clear amount', $response->getClearingAmount());
        $this->assertEquals('exp  proc result', $response->getProcessingResult());
        $this->assertEquals('exp name saluation', $response->getNameSalutation());
        $this->assertEquals('exp presen usage', $response->getPresentationUsage());
        $this->assertEquals('exp post valida', $response->getPostValidation());
        $this->assertEquals($this->orderEntity->getCustomer()->getEmail(), $response->getContactEmail());
        $this->assertEquals('exp clear  currency', $response->getClearingCurrency());
        $this->assertEquals('exp frontend session', $response->getFrontendSessionId());
        $this->assertEquals('exp proc stat code', $response->getProcessingStatusCode());
        $this->assertEquals('exp presentation currency', $response->getPresentationCurrency());
        $this->assertEquals('exp payment code', $response->getPaymentCode());
        $this->assertEquals($this->orderEntity->getCustomer()->getDateOfBirth('Y-m-d'), $response->getNameBirthdate());
        $this->assertEquals('exp proc return code', $response->getProcessingReturnCode());
        $this->assertEquals($paymentEntity->getClientIp(), $response->getContactIp());
        $this->assertEquals($this->orderEntity->getLastName(), $response->getNameFamily());
        $this->assertEquals('exp proc stat', $response->getProcessingStatus());
        $this->assertEquals('exp frontend cc logo', $response->getFrontendCcLogo());
        $this->assertEquals($paymentEntity->getSpySalesOrder()->getGrandTotal(), $response->getPresentationAmount());
        $this->assertEquals('exp id unique', $response->getIdentificationUniqueid());
        $this->assertEquals('exp id trans id', $response->getIdentificationTransactionid());
        $this->assertEquals('exp  id short id', $response->getIdentificationShortid());
        $this->assertEquals('exp clearingfx rate', $response->getClearingFxrate());
        $this->assertEquals('exp proc timestamp', $response->getProcessingTimestamp());
        $this->assertEquals($this->orderEntity->getBillingAddress()->getCountry(), $response->getAddressCountry());
        $this->assertEquals('exp proc connectri detail pay ref', $response->getProcessingConnectordetailPaymentreference());
        $this->assertEquals('exp resp version', $response->getResponseVersion());
        $this->assertEquals('CONNECTOR TEST', $response->getTransactionMode());
        $this->assertEquals('exp proc return',  $response->getProcessingReturn());
        $this->assertEquals('exp trans response', $response->getTransactionResponse());
        $this->assertEquals($this->orderEntity->getBillingAddress()->getAddress1(), $response->getAddressStreet());
        $this->assertEquals($this->orderEntity->getCustomer()->getGender(), $response->getNameSex());
        $this->assertEquals('exp clear fx date', $response->getClearingFxdate());
        $this->assertEquals($this->orderEntity->getBillingAddress()->getZipCode(), $response->getAddressZip());
        
        // @todo CD-408 Assert persistent data
        // @todo CD-408 Assert response data
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function setTestData()
    {
        $country = SpyCountryQuery::create()->findOneByIso2Code('de');

        $billingAddress = (new SpySalesOrderAddress())
            ->setFkCountry($country->getIdCountry())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setAddress1('Straße des 17. Juni 135')
            ->setCity('Berlin')
            ->setZipCode('10623');
        $billingAddress->save();

        $customer = (new SpyCustomer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john@doe.com')
            ->setDateOfBirth('1970-01-01')
            ->setGender(SpyCustomerTableMap::COL_GENDER_MALE)
            ->setCustomerReference('payolution-pre-authorization-test');
        $customer->save();

        $this->orderEntity = (new SpySalesOrder())
            ->setEmail('john@doe.com')
            ->setGrandTotal(10000)
            ->setSubtotal(10000)
            ->setIsTest(true)
            ->setFkSalesOrderAddressBilling($billingAddress->getIdSalesOrderAddress())
            ->setFkSalesOrderAddressShipping($billingAddress->getIdSalesOrderAddress())
            ->setCustomer($customer)
            ->setOrderReference('foo-bar-baz-2');
        $this->orderEntity->save();
    }

    /**
     * @return AutoCompletion|Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
