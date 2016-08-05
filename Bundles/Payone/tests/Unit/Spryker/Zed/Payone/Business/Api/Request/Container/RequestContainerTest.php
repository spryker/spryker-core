<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer as AuthorizationBusinessContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CashOnDeliveryContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\DirectDebitContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\EWalletContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\FinancingContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\OnlineBankTransferContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\PrepaymentContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer as CaptureBusinessContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer as DebitBusinessContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\BankAccountContainer as DebitBankAccountContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\GetInvoiceContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer as RefundBankAccountContainer;

/**
 * @group RequestContainer
 */
class RequestContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var int
     */
    protected $amount = 9900;

    /**
     * @var string
     */
    protected $encoding = 'UTF-8';

    /**
     * @var string
     */
    protected $currency = 'EUR';

    /**
     * @var int
     */
    protected $sequenceNumber = 2;

    /**
     * @var string
     */
    protected $mode = 'test';

    /**
     * @var string
     */
    protected $txId = '123456789';

    /**
     * @var string
     */
    protected $portalId = '12345';

    /**
     * @var string
     */
    protected $mid = '123';

    /**
     * @var string
     */
    protected $aid = '1234';

    /**
     * @var string
     */
    protected $integratorName = 'integrator-name';

    /**
     * @var string
     */
    protected $integratorVersion = '1.0';

    /**
     * @var string
     */
    protected $solutionName = 'solution-name';

    /**
     * @var string
     */
    protected $solutionVersion = '2.0';

    /**
     * @var string
     */
    protected $key = '123456789-test-key';

    /**
     * @var string
     */
    protected $reference = 'DE000000001';

    /**
     * @var string
     */
    protected $clearingType = 'pre';

    /**
     * @var string
     */
    protected $narrativeText = 'some-text';

    /**
     * @return void
     */
    public function testRefundContainer()
    {
        $container = new RefundContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setSequenceNumber($this->sequenceNumber);
        $container->setTxid($this->txId);
        $container->setInvoicing(new TransactionContainer());

        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->sequenceNumber, $container->getSequenceNumber());
        $this->assertEquals($this->txId, $container->getTxid());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
    }

    /**
     * @return void
     */
    public function testEmptyRefundContainer()
    {
        $container = new RefundContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testDebitContainer()
    {
        $container = new DebitContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setSequenceNumber($this->sequenceNumber);
        $container->setTxid($this->txId);
        $container->setBusiness(new DebitBusinessContainer());
        $container->setInvoicing(new TransactionContainer());

        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->sequenceNumber, $container->getSequenceNumber());
        $this->assertEquals($this->txId, $container->getTxid());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer', $container->getBusiness());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
    }

    /**
     * @return void
     */
    public function testEmptyDebitContainer()
    {
        $container = new DebitContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testCaptureContainer()
    {
        $container = new CaptureContainer();

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setSequenceNumber($this->sequenceNumber);
        $container->setTxid($this->txId);
        $container->setBusiness(new CaptureBusinessContainer());
        $container->setInvoicing(new TransactionContainer());

        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->sequenceNumber, $container->getSequenceNumber());
        $this->assertEquals($this->txId, $container->getTxid());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer', $container->getBusiness());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
    }

    /**
     * @return void
     */
    public function testEmptyCaptureContainer()
    {
        $container = new CaptureContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testAuthorizationContainer()
    {
        $container = new AuthorizationContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setAid($this->aid);
        $container->setReference($this->reference);
        $container->setClearingType($this->clearingType);
        $container->setNarrativeText($this->narrativeText);
        $container->setBusiness(new AuthorizationBusinessContainer());
        $container->setInvoicing(new TransactionContainer());
        $container->set3dsecure(new ThreeDSecureContainer());
        $container->setPersonalData(new PersonalContainer());
        $container->setPaymentMethod(new PrepaymentContainer());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, $container->getRequest());
        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->reference, $container->getReference());
        $this->assertEquals($this->aid, $container->getAid());
        $this->assertEquals($this->clearingType, $container->getClearingType());
        $this->assertEquals($this->narrativeText, $container->getNarrativeText());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer', $container->getBusiness());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer', $container->get3dsecure());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer', $container->getPersonalData());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\PrepaymentContainer', $container->getPaymentMethod());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container->getPaymentMethod());
    }

    /**
     * @return void
     */
    public function testEmptyAuthorizationContainer()
    {
        $container = new AuthorizationContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testPreAuthorizationContainer()
    {
        $container = new PreAuthorizationContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setAid($this->aid);
        $container->setReference($this->reference);
        $container->setClearingType($this->clearingType);
        $container->setNarrativeText($this->narrativeText);
        $container->setInvoicing(new TransactionContainer());
        $container->set3dsecure(new ThreeDSecureContainer());
        $container->setPersonalData(new PersonalContainer());
        $container->setPaymentMethod(new PrepaymentContainer());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, $container->getRequest());
        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->reference, $container->getReference());
        $this->assertEquals($this->aid, $container->getAid());
        $this->assertEquals($this->clearingType, $container->getClearingType());
        $this->assertEquals($this->narrativeText, $container->getNarrativeText());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer', $container->get3dsecure());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer', $container->getPersonalData());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\PrepaymentContainer', $container->getPaymentMethod());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container->getPaymentMethod());
    }

    /**
     * @return void
     */
    public function testEmptyPreAuthorizationContainer()
    {
        $container = new PreAuthorizationContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testBankAccountCheckContainer()
    {
        $container = new BankAccountCheckContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAid($this->aid);
        $this->assertEquals($this->aid, $container->getAid());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBankCode('code');
        $this->assertEquals('code', $container->getBankCode());

        $container->setBankAccount('account');
        $this->assertEquals('account', $container->getBankAccount());

        $container->setCheckType('checktype');
        $this->assertEquals('checktype', $container->getCheckType());

        $container->setLanguage('language');
        $this->assertEquals('language', $container->getLanguage());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_BANKACCOUNTCHECK, $container->getRequest());
        $this->assertStandardParams($container);
    }

    /**
     * @return void
     */
    public function testEmptyBankAccountCheckContainer()
    {
        $container = new BankAccountCheckContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testGetInvoiceContainer()
    {
        $container = new GetInvoiceContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setInvoiceTitle('invoicetitle');
        $this->assertEquals('invoicetitle', $container->getInvoiceTitle());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_GETINVOICE, $container->getRequest());
        $this->assertStandardParams($container);
    }

    /**
     * @return void
     */
    public function testEmptyGetInvoiceContainer()
    {
        $container = new GetInvoiceContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testPersonalContainer()
    {
        $container = new PersonalContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setAddressAddition('addition');
        $this->assertEquals('addition', $container->getAddressAddition());

        $container->setBirthday('2000-01-01');
        $this->assertEquals('2000-01-01', $container->getBirthday());

        $container->setCity('city');
        $this->assertEquals('city', $container->getCity());

        $container->setCompany('company');
        $this->assertEquals('company', $container->getCompany());

        $container->setCountry('country');
        $this->assertEquals('country', $container->getCountry());

        $container->setCustomerId('cid');
        $this->assertEquals('cid', $container->getCustomerId());

        $container->setEmail('email');
        $this->assertEquals('email', $container->getEmail());

        $container->setFirstName('firstname');
        $this->assertEquals('firstname', $container->getFirstName());

        $container->setLastName('lastname');
        $this->assertEquals('lastname', $container->getLastName());

        $container->setLanguage('language');
        $this->assertEquals('language', $container->getLanguage());

        $container->setIp('ip');
        $this->assertEquals('ip', $container->getIp());

        $container->setSalutation('salutation');
        $this->assertEquals('salutation', $container->getSalutation());

        $container->setState('state');
        $this->assertEquals('state', $container->getState());

        $container->setStreet('street');
        $this->assertEquals('street', $container->getStreet());

        $container->setTelephoneNumber('phonenumber');
        $this->assertEquals('phonenumber', $container->getTelephoneNumber());

        $container->setTitle('title');
        $this->assertEquals('title', $container->getTitle());

        $container->setUserId('userid');
        $this->assertEquals('userid', $container->getUserId());

        $container->setVatId('vatid');
        $this->assertEquals('vatid', $container->getVatId());

        $container->setZip('zip');
        $this->assertEquals('zip', $container->getZip());

        $this->assertCount(19, $container->toArray());
    }

    /**
     * @return void
     */
    public function testPrepaymentContainer()
    {
        $container = new PrepaymentContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setClearingBankAccount('account');
        $this->assertEquals('account', $container->getClearingBankAccount());

        $container->setClearingBankAccountHolder('holder');
        $this->assertEquals('holder', $container->getClearingBankAccountHolder());

        $container->setClearingBankBic('bic');
        $this->assertEquals('bic', $container->getClearingBankBic());

        $container->setClearingBankCity('city');
        $this->assertEquals('city', $container->getClearingBankCity());

        $container->setClearingBankCode('code');
        $this->assertEquals('code', $container->getClearingBankCode());

        $container->setClearingBankCountry('country');
        $this->assertEquals('country', $container->getClearingBankCountry());

        $container->setClearingBankIban('iban');
        $this->assertEquals('iban', $container->getClearingBankIban());

        $container->setClearingBankName('name');
        $this->assertEquals('name', $container->getClearingBankName());
    }

    /**
     * @return void
     */
    public function testEWalletContainer()
    {
        $container = new EWalletContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setWalletType('type');
        $this->assertEquals('type', $container->getWalletType());

        $container->setRedirect(new RedirectContainer());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer', $container->getRedirect());

        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testOnlineBankTransferContainer()
    {
        $container = new OnlineBankTransferContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBankAccount('account');
        $this->assertEquals('account', $container->getBankAccount());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBankCode('code');
        $this->assertEquals('code', $container->getBankCode());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setOnlineBankTransferType('transfertype');
        $this->assertEquals('transfertype', $container->getOnlineBankTransferType());

        $container->setBankGroupType('grouptype');
        $this->assertEquals('grouptype', $container->getBankGroupType());

        $container->setRedirect(new RedirectContainer());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer', $container->getRedirect());

        $this->assertCount(7, $container->toArray());
    }

    /**
     * @return void
     */
    public function testCashOnDeliveryContainer()
    {
        $container = new CashOnDeliveryContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setShippingProvider('shippingprovider');
        $this->assertEquals('shippingprovider', $container->getShippingProvider());

        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testDirectDebitContainer()
    {
        $container = new DirectDebitContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setBankCode('bankcode');
        $this->assertEquals('bankcode', $container->getBankCode());

        $container->setBankAccount('bankaccount');
        $this->assertEquals('bankaccount', $container->getBankAccount());

        $container->setBankAccountHolder('bankaccountholder');
        $this->assertEquals('bankaccountholder', $container->getBankAccountHolder());

        $container->setBankCountry('bankcountry');
        $this->assertEquals('bankcountry', $container->getBankCountry());

        $container->setMandateIdentification('mandatidentification');
        $this->assertEquals('mandatidentification', $container->getMandateIdentification());

        $this->assertCount(7, $container->toArray());
    }

    /**
     * @return void
     */
    public function testFinancingContainer()
    {
        $container = new FinancingContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setFinancingType('type');
        $this->assertEquals('type', $container->getFinancingType());

        $container->setRedirect(new RedirectContainer());
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer', $container->getRedirect());

        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyFinancingContainer()
    {
        $container = new FinancingContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function test3DSecureContainer()
    {
        $container = new ThreeDSecureContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setCavv('cavv');
        $this->assertEquals('cavv', $container->getCavv());

        $container->setEci('eci');
        $this->assertEquals('eci', $container->getEci());

        $container->setXid('xid');
        $this->assertEquals('xid', $container->getXid());

        $this->assertCount(3, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmpty3DSecureContainer()
    {
        $container = new ThreeDSecureContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testRedirectContainer()
    {
        $container = new RedirectContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBackUrl('backurl');
        $this->assertEquals('backurl', $container->getBackUrl());

        $container->setErrorUrl('errorurl');
        $this->assertEquals('errorurl', $container->getErrorUrl());

        $container->setSuccessUrl('successurl');
        $this->assertEquals('successurl', $container->getSuccessUrl());

        $this->assertCount(3, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyRedirectContainer()
    {
        $container = new RedirectContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testInvoicingTransactionContainer()
    {
        $container = new TransactionContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setInvoiceappendix('appendix');
        $this->assertEquals('appendix', $container->getInvoiceappendix());

        $container->setInvoiceDeliverydate('deliverydate');
        $this->assertEquals('deliverydate', $container->getInvoiceDeliverydate());

        $container->setInvoiceDeliveryenddate('deliveryenddate');
        $this->assertEquals('deliveryenddate', $container->getInvoiceDeliveryenddate());

        $container->setInvoiceDeliverymode('deliverymode');
        $this->assertEquals('deliverymode', $container->getInvoiceDeliverymode());

        $container->setInvoiceid('invoiceid');
        $this->assertEquals('invoiceid', $container->getInvoiceid());

        $items = [new ItemContainer(), new ItemContainer()];
        $container->setItems($items);

        $this->assertCount(6, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyInvoicingTransactionContainer()
    {
        $container = new TransactionContainer();
        $this->assertCount(1, $container->toArray()); // 1 empty array
    }

    /**
     * @return void
     */
    public function testInvoicingItemContainer()
    {
        $container = new ItemContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setDe('de');
        $this->assertEquals('de', $container->getDe());

        $container->setEd('ed');
        $this->assertEquals('ed', $container->getEd());

        $container->setId('id');
        $this->assertEquals('id', $container->getId());

        $container->setIt('it');
        $this->assertEquals('it', $container->getIt());

        $container->setNo('no');
        $this->assertEquals('no', $container->getNo());

        $container->setPr('pr');
        $this->assertEquals('pr', $container->getPr());

        $container->setSd('sd');
        $this->assertEquals('sd', $container->getSd());

        $container->setVa('va');
        $this->assertEquals('va', $container->getVa());

        $this->assertCount(8, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyInvoicingItemContainer()
    {
        $container = new ItemContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testAuthorizationBusinessContainer()
    {
        $container = new AuthorizationBusinessContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBookingDate('bookingdate');
        $this->assertEquals('bookingdate', $container->getBookingDate());

        $container->setDocumentDate('documentdate');
        $this->assertEquals('documentdate', $container->getDocumentDate());

        $container->setDueTime('duetime');
        $this->assertEquals('duetime', $container->getDueTime());

        $this->assertCount(3, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyAuthorizationBusinessContainer()
    {
        $container = new AuthorizationBusinessContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testCaptureBusinessContainer()
    {
        $container = new CaptureBusinessContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBookingDate('bookingdate');
        $this->assertEquals('bookingdate', $container->getBookingDate());

        $container->setDocumentDate('documentdate');
        $this->assertEquals('documentdate', $container->getDocumentDate());

        $container->setDueTime('duetime');
        $this->assertEquals('duetime', $container->getDueTime());

        $container->setSettleAccount('settleaccout');
        $this->assertEquals('settleaccout', $container->getSettleAccount());

        $this->assertCount(4, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyCaptureBusinessContainer()
    {
        $container = new CaptureBusinessContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testDebitBusinessContainer()
    {
        $container = new DebitBusinessContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBookingDate('bookingdate');
        $this->assertEquals('bookingdate', $container->getBookingDate());

        $container->setDocumentDate('documentdate');
        $this->assertEquals('documentdate', $container->getDocumentDate());

        $container->setTransactionType('transactiontype');
        $this->assertEquals('transactiontype', $container->getTransactionType());

        $container->setSettleAccount('settleaccout');
        $this->assertEquals('settleaccout', $container->getSettleAccount());

        $this->assertCount(4, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyDebitBusinessContainer()
    {
        $container = new DebitBusinessContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testAuthorizationShippingContainer()
    {
        $container = new ShippingContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setShippingCity('city');
        $this->assertEquals('city', $container->getShippingCity());

        $container->setShippingCompany('company');
        $this->assertEquals('company', $container->getShippingCompany());

        $container->setShippingCountry('country');
        $this->assertEquals('country', $container->getShippingCountry());

        $container->setShippingFirstName('firstname');
        $this->assertEquals('firstname', $container->getShippingFirstName());

        $container->setShippingLastName('lastname');
        $this->assertEquals('lastname', $container->getShippingLastName());

        $container->setShippingState('state');
        $this->assertEquals('state', $container->getShippingState());

        $container->setShippingStreet('street');
        $this->assertEquals('street', $container->getShippingStreet());

        $container->setShippingZip('zip');
        $this->assertEquals('zip', $container->getShippingZip());

        $this->assertCount(8, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyAuthorizationShippingContainer()
    {
        $container = new ShippingContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testDebitBankAccountContainer()
    {
        $container = new DebitBankAccountContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBankAccount('bankaccount');
        $this->assertEquals('bankaccount', $container->getBankAccount());

        $container->setBankAccountHolder('holder');
        $this->assertEquals('holder', $container->getBankAccountHolder());

        $container->setBankBranchCode('branchcode');
        $this->assertEquals('branchcode', $container->getBankBranchCode());

        $container->setBankCheckDigit('checkdigit');
        $this->assertEquals('checkdigit', $container->getBankCheckDigit());

        $container->setBankCode('code');
        $this->assertEquals('code', $container->getBankCode());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setMandateIdentification('mandateidentifiction');
        $this->assertEquals('mandateidentifiction', $container->getMandateIdentification());

        $this->assertCount(9, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyDebitBankAccountContainer()
    {
        $container = new DebitBankAccountContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testRefundBankAccountContainer()
    {
        $container = new RefundBankAccountContainer();
        $this->assertInstanceOf('Spryker\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBankAccount('bankaccount');
        $this->assertEquals('bankaccount', $container->getBankAccount());

        $container->setBankBranchCode('branchcode');
        $this->assertEquals('branchcode', $container->getBankBranchCode());

        $container->setBankCheckDigit('checkdigit');
        $this->assertEquals('checkdigit', $container->getBankCheckDigit());

        $container->setBankCode('code');
        $this->assertEquals('code', $container->getBankCode());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $this->assertCount(7, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyRefundBankAccountContainer()
    {
        $container = new RefundBankAccountContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
     */
    protected function setStandardParams(AbstractRequestContainer $container)
    {
        $container->setEncoding($this->encoding);
        $container->setMode($this->mode);
        $container->setPortalid($this->portalId);
        $container->setMid($this->mid);
        $container->setIntegratorName($this->integratorName);
        $container->setIntegratorVersion($this->integratorVersion);
        $container->setSolutionName($this->solutionName);
        $container->setSolutionVersion($this->solutionVersion);
        $container->setKey($this->key);
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
     */
    protected function assertStandardParams(AbstractRequestContainer $container)
    {
        $this->assertEquals($this->encoding, $container->getEncoding());
        $this->assertEquals($this->mode, $container->getMode());
        $this->assertEquals($this->portalId, $container->getPortalid());
        $this->assertEquals($this->mid, $container->getMid());
        $this->assertEquals($this->key, $container->getKey());
        $this->assertEquals($this->integratorName, $container->getIntegratorName());
        $this->assertEquals($this->integratorVersion, $container->getIntegratorVersion());
        $this->assertEquals($this->solutionName, $container->getSolutionName());
        $this->assertEquals($this->solutionVersion, $container->getSolutionVersion());
    }

}
