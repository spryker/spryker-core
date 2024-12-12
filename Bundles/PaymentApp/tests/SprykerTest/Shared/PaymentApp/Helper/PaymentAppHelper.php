<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PaymentApp\Helper;

use Codeception\Module;
use Codeception\Stub;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\EndpointTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentMethodAppConfigurationTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface;
use Spryker\Zed\Payment\Business\PaymentFacade;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeBridge;
use Spryker\Zed\PaymentApp\PaymentAppConfig;
use Spryker\Zed\PaymentApp\PaymentAppDependencyProvider;
use SprykerTest\Shared\Payment\Helper\PaymentDataHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;

class PaymentAppHelper extends Module
{
    use PaymentDataHelperTrait;
    use DependencyHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function havePaymentMethodWithoutPaymentMethodAppConfigurationPersisted(): PaymentMethodTransfer
    {
        $seedData = $this->getDefaultPaymentMethodSeedData();

        return $this->getPaymentDataHelper()->havePaymentMethodWithPaymentProviderPersisted($seedData);
    }

    /**
     * @param array $paymentMethodAppConfigurationSeed
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function havePaymentMethodWithPaymentMethodAppConfigurationPersisted(array $paymentMethodAppConfigurationSeed = []): PaymentMethodTransfer
    {
        $seedData = $this->getDefaultPaymentMethodSeedData();
        $seedData[PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION] = $paymentMethodAppConfigurationSeed;

        return $this->getPaymentDataHelper()->havePaymentMethodWithPaymentProviderPersisted($seedData);
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function havePaymentMethodWithPaymentMethodAppConfigurationForCustomerEndpointPersisted(): PaymentMethodTransfer
    {
        $seedData = $this->getDefaultPaymentMethodSeedData();
        $seedData += [
            PaymentMethodTransfer::PAYMENT_METHOD_APP_CONFIGURATION => [
                PaymentMethodAppConfigurationTransfer::BASE_URL => 'http://foo.bar',
                PaymentMethodAppConfigurationTransfer::ENDPOINTS => [
                    [
                        EndpointTransfer::NAME => PaymentAppConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_CUSTOMER,
                        EndpointTransfer::PATH => '/customer',
                    ],
                ],
            ],
        ];

        return $this->getPaymentDataHelper()->havePaymentMethodWithPaymentProviderPersisted($seedData);
    }

    /**
     * @return array
     */
    protected function getDefaultPaymentMethodSeedData(): array
    {
        $paymentMethodName = 'method-' . Uuid::uuid4()->toString();
        $paymentProviderKey = 'provider-' . Uuid::uuid4()->toString();

        return [
            PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            PaymentMethodTransfer::IS_ACTIVE => true,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => (new PaymentFacade())->generatePaymentMethodKey($paymentProviderKey, $paymentMethodName),
            PaymentMethodTransfer::NAME => $paymentMethodName,
            PaymentMethodTransfer::PAYMENT_PROVIDER => [
                PaymentProviderTransfer::NAME => $paymentProviderKey,
                PaymentProviderTransfer::PAYMENT_PROVIDER_KEY => $paymentProviderKey,
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer|null $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerRequestTransfer
     */
    public function havePaymentCustomerRequestTransfer(?PaymentMethodTransfer $paymentMethodTransfer = null): PaymentCustomerRequestTransfer
    {
        $paymentMethodName = $this->getPaymentMethodName($paymentMethodTransfer);
        $paymentProviderName = $this->getPaymentProviderName($paymentMethodTransfer);

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer
            ->setPaymentMethodName($paymentMethodName)
            ->setPaymentProviderName($paymentProviderName);

        $paymentCustomerRequestTransfer = new PaymentCustomerRequestTransfer();
        $paymentCustomerRequestTransfer
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData([
                'foo' => 'bar',
            ]);

        return $paymentCustomerRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer|null $paymentMethodTransfer
     *
     * @return string
     */
    protected function getPaymentMethodName(?PaymentMethodTransfer $paymentMethodTransfer = null): string
    {
        if ($paymentMethodTransfer && $paymentMethodTransfer->getName()) {
            return $paymentMethodTransfer->getName();
        }

        return 'method-' . Uuid::uuid4()->toString();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer|null $paymentMethodTransfer
     *
     * @return string
     */
    protected function getPaymentProviderName(?PaymentMethodTransfer $paymentMethodTransfer = null): string
    {
        if ($paymentMethodTransfer && $paymentMethodTransfer->getPaymentProvider() && $paymentMethodTransfer->getPaymentProvider()->getName()) {
            return $paymentMethodTransfer->getPaymentProvider()->getName();
        }

        return 'provider-' . Uuid::uuid4()->toString();
    }

    /**
     * @return \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer
     */
    public function havePreOrderPaymentRequestTransferWithoutQuote(): PreOrderPaymentRequestTransfer
    {
        return new PreOrderPaymentRequestTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer
     */
    public function havePreOrderPaymentRequestTransferWithQuote(): PreOrderPaymentRequestTransfer
    {
        $quoteBuilder = new QuoteBuilder();
        $quoteTransfer = $quoteBuilder->withItem()->build();

        $preOrderPaymentRequestTransfer = new PreOrderPaymentRequestTransfer();
        $preOrderPaymentRequestTransfer->setQuote($quoteTransfer);

        return $preOrderPaymentRequestTransfer;
    }

    /**
     * @param string $expectedResponseCode
     * @param array|string $expectedResponseData
     *
     * @return void
     */
    public function mockKernelAppFacadeResponse(string $expectedResponseCode, array|string $expectedResponseData): void
    {
        // Mock the KernelApp response
        $kernelAppFacadeMock = Stub::makeEmpty(KernelAppFacadeInterface::class, [
            'makeRequest' => function () use ($expectedResponseCode, $expectedResponseData) {
                $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
                $acpHttpResponseTransfer
                    ->setHttpStatusCode($expectedResponseCode)
                    ->setContent(is_string($expectedResponseData) ? $expectedResponseData : json_encode($expectedResponseData));

                return $acpHttpResponseTransfer;
            },
        ]);

        $this->getDependencyHelper()->setDependency(PaymentAppDependencyProvider::FACADE_KERNEL_APP, new PaymentAppToKernelAppFacadeBridge($kernelAppFacadeMock));
    }
}
