<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\Sales\Business\SalesBusinessFactory;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Sales\SalesConfig;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderExpanderPluginInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group UpdateSalesOrderItemCollectionTest
 * Add your own group annotations below this line
 */
class UpdateSalesOrderItemCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const FAKE_ID_SALES_ORDER = 123;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Business\Updater\OrderItemScheduleUpdater::GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_FOUND = 'self_service_portal.service.validation.order_not_found';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Business\Updater\OrderItemScheduleUpdater::GLOSSARY_KEY_VALIDATION_NO_PAYMENT_METHODS_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_NO_PAYMENT_METHODS_FOUND = 'self_service_portal.service.validation.no_payment_methods_found';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Business\Updater\OrderItemScheduleUpdater::GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED = 'self_service_portal.service.validation.no_order_items_provided';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected SelfServicePortalBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->salesFacade = $this->getSalesFacadeMock();
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderItemCollectionShouldReturnErrorWhenNoItemsProvided(): void
    {
        // Arrange
        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
        $salesOrderItemCollectionRequestTransfer->setItems(new ArrayObject());

        // Expect
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderItemCollectionShouldReturnErrorWhenOrderNotFound(): void
    {
        // Arrange
        $itemTransfer = (new ItemBuilder([ItemTransfer::FK_SALES_ORDER => static::FAKE_ID_SALES_ORDER]))->build();

        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
        $salesOrderItemCollectionRequestTransfer->setItems(new ArrayObject([$itemTransfer]));

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->tester->getFacade()->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertTrue($salesOrderItemCollectionResponseTransfer->getErrors()->count() > 0);
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_FOUND,
            $salesOrderItemCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderItemCollectionShouldReturnErrorWhenNoPaymentMethodsFound(): void
    {
        // Arrange
        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentProviderPersisted($this->getDefaultPaymentMethodSeedData());
        $quoteTransfer = $this->prepareQuoteWithOneItem($paymentMethodTransfer);

        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
        $salesOrderItemCollectionRequestTransfer->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->tester->getFacade()->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertTrue($salesOrderItemCollectionResponseTransfer->getErrors()->count() > 0);
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_NO_PAYMENT_METHODS_FOUND,
            $salesOrderItemCollectionResponseTransfer->getErrors()[0]->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateSalesOrderItemCollectionShouldSuccessfullyUpdateSalesOrderItems(): void
    {
        // Arrange
        $this->tester->setDependency(SelfServicePortalDependencyProvider::FACADE_SALES, $this->salesFacade);
        $this->tester->setDependency(
            SalesDependencyProvider::HYDRATE_ORDER_PLUGINS,
            [
                new class extends AbstractPlugin implements OrderExpanderPluginInterface {
                    /**
                     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
                     *
                     * @return \Generated\Shared\Transfer\OrderTransfer
                     */
                    public function hydrate(OrderTransfer $orderTransfer): OrderTransfer
                    {
                        return $orderTransfer->setPayments(new ArrayObject([
                            (new PaymentTransfer())->setPaymentMethod('foo-bar')
                                ->setPaymentProvider('foo-bar'),
                        ]));
                    }
                },
            ],
        );

        $paymentMethodTransfer = $this->tester->havePaymentMethodWithPaymentProviderPersisted($this->getDefaultPaymentMethodSeedData());
        $quoteTransfer = $this->prepareQuoteWithOneItem($paymentMethodTransfer, true);

        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();
        $salesOrderItemCollectionRequestTransfer->setItems($quoteTransfer->getItems());

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->tester->getFacade()->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        $this->assertCount(0, $salesOrderItemCollectionResponseTransfer->getErrors());
        $this->assertEquals($quoteTransfer->getItems()[0], $salesOrderItemCollectionResponseTransfer->getItems()[0]);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacadeMock(): SalesFacadeInterface
    {
        $businessFactory = new SalesBusinessFactory();

        $salesConfigMock = $this->getMockBuilder(SalesConfig::class)->onlyMethods(['determineProcessForOrderItem', 'isOldDeterminationForOrderItemProcessEnabled'])->getMock();
        $salesConfigMock->method('determineProcessForOrderItem')->willReturn(static::DEFAULT_OMS_PROCESS_NAME);
        $salesConfigMock->method('isOldDeterminationForOrderItemProcessEnabled')->willReturn(true);

        $businessFactory->setConfig($salesConfigMock);

        $salesFacade = $this->tester->getLocator()->sales()->facade();
        $salesFacade->setFactory($businessFactory);

        return $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param bool $savePayment

     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function prepareQuoteWithOneItem(PaymentMethodTransfer $paymentMethodTransfer, bool $savePayment = false): QuoteTransfer
    {
        $saveOrderTransfer = new SaveOrderTransfer();
        $quoteTransfer = $this->tester->getValidBaseQuoteTransfer($paymentMethodTransfer);
        $this->salesFacade->saveOrderRaw($quoteTransfer, $saveOrderTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setFkSalesOrder($saveOrderTransfer->getIdSalesOrder());
        }

        $this->salesFacade->saveSalesOrderItems($quoteTransfer, $saveOrderTransfer);
        $this->salesFacade->saveSalesOrderTotals($quoteTransfer, $saveOrderTransfer);

        if ($savePayment) {
            $this->tester->getLocator()->salesPayment()->facade()->saveOrderPayments($quoteTransfer, $saveOrderTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @return array
     */
    protected function getDefaultPaymentMethodSeedData(): array
    {
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        return [
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_PROVIDER => [
                PaymentProviderTransfer::NAME => $paymentProviderKey,
                PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            ],
        ];
    }
}
