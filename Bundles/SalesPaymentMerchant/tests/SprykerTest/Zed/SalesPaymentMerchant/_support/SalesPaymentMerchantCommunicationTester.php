<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\SalesPaymentMerchant;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\EndpointTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodAppConfigurationTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface;
use Spryker\Zed\Sales\SalesDependencyProvider;
use Spryker\Zed\SalesPayment\Communication\Plugin\Sales\SalesPaymentOrderExpanderPlugin;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToKernelAppFacadeBridge;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantDependencyProvider;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesPaymentMerchantCommunicationTester extends Actor
{
    use _generated\SalesPaymentMerchantCommunicationTesterActions;

    /**
     * @return void
     */
    public function mockHydrateOrderPluginsInSalesModule(): void
    {
        $this->setDependency(SalesDependencyProvider::HYDRATE_ORDER_PLUGINS, [
            new SalesPaymentOrderExpanderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function havePaymentProviderWithPaymentMethodSupportingPayouts(): void
    {
        $paymentProviderTransfer = $this->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'bar',
            PaymentProviderTransfer::NAME => 'bar',
        ]);

        $this->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'Foo',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://localhost:8080',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => 'transfer',
                        EndpointTransfer::PATH => '/payouts',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function havePaymentProviderWithPaymentMethod(): void
    {
        $paymentProviderTransfer = $this->havePaymentProvider([
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => 'bar',
            PaymentProviderTransfer::NAME => 'bar',
        ]);

        $this->havePaymentMethod([
            PaymentMethodTransfer::NAME => 'Foo',
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => 'foo-bar',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
    }

    /**
     * @param array $orderItems
     * @param string|null $merchantReference
     * @param string|null $orderReference
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function mockSalesOrderEntity(
        array $orderItems,
        ?string $merchantReference = null,
        ?string $orderReference = null
    ): SpySalesOrder {
        $merchantReference = $merchantReference ?? Uuid::uuid4()->toString();
        $orderReference = $orderReference ?? Uuid::uuid4()->toString();

        $saveOrderTransfer = $this->haveOrder([
            ItemTransfer::MERCHANT_REFERENCE => $merchantReference,
            QuoteTransfer::ORDER_REFERENCE => $orderReference,
        ], 'ForeignPaymentStateMachine01');

        $salesPaymentMethodTypeTransfer = $this->haveSalesPaymentMethodTypePersisted([
            SalesPaymentMethodTypeTransfer::PAYMENT_PROVIDER => 'foo',
            SalesPaymentMethodTypeTransfer::PAYMENT_METHOD => 'bar',
        ]);
        $this->haveSalesPaymentPersisted([
            SalesPaymentTransfer::FK_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            SalesPaymentTransfer::FK_PAYMENT_METHOD_TYPE => $salesPaymentMethodTypeTransfer->getIdSalesPaymentMethodType(),
            SalesPaymentTransfer::AMOUNT => 10000000,
        ]);

        $persistedOrderItems = [];
        foreach ($orderItems as $orderItem) {
            $persistedOrderItems[] = $this->createSalesOrderItemForOrder(
                $saveOrderTransfer->getIdSalesOrder(),
                $orderItem->toArray(),
            );
        }

        return $this->createSalesOrderEntity([
            OrderTransfer::ID_SALES_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            OrderTransfer::ITEMS => $persistedOrderItems,
            OrderTransfer::ORDER_REFERENCE => $orderReference,
        ]);
    }

    /**
     * @param string $merchantReference
     * @param string $orderReference
     * @param array<int, array<string, string>> $orderItemReferences
     * @param string $amount
     *
     * @return void
     */
    public function mockExpectedResponseFromApp(
        string $merchantReference,
        string $orderReference,
        array $orderItemReferences,
        string $amount
    ): void {
        $string = json_encode([
            'transfers' => [
                [
                    'merchantReference' => $merchantReference,
                    'orderReference' => $orderReference,
                    'orderItems' => $orderItemReferences,
                    'amount' => $amount,
                    'isSuccessful' => true,
                ],
            ],
        ]);

        $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
        $acpHttpResponseTransfer->setContent($string);

        $kernelAppFacadeMock = Stub::makeEmpty(KernelAppFacadeInterface::class, [
            'makeRequest' => $acpHttpResponseTransfer,
        ]);

        $this->setDependency(SalesPaymentMerchantDependencyProvider::FACADE_KERNEL_APP, new SalesPaymentMerchantToKernelAppFacadeBridge($kernelAppFacadeMock));
    }
}
