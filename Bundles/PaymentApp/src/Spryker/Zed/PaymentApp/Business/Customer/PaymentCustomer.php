<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Business\Customer;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodConditionsTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\PaymentApp\Business\Exception\PaymentAppEndpointNotFoundException;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeInterface;
use Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface;
use Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceInterface;
use Spryker\Zed\PaymentApp\PaymentAppConfig;
use Symfony\Component\HttpFoundation\Request;

class PaymentCustomer implements PaymentCustomerInterface
{
    /**
     * @param \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\PaymentApp\Dependency\Facade\PaymentAppToKernelAppFacadeInterface $kernelAppFacade
     * @param \Spryker\Zed\PaymentApp\Dependency\Service\PaymentAppToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        protected PaymentAppToPaymentFacadeInterface $paymentFacade,
        protected PaymentAppToKernelAppFacadeInterface $kernelAppFacade,
        protected PaymentAppToUtilEncodingServiceInterface $utilEncodingService
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomer(
        PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
    ): PaymentCustomerResponseTransfer {
        $paymentMethodTransfer = $this->getPaymentMethodTransferFromRequestTransfer($paymentCustomerRequestTransfer);

        $paymentMethodConditionTransfer = new PaymentMethodConditionsTransfer();
        $paymentMethodConditionTransfer
            ->setPaymentMethodKeys([$paymentMethodTransfer->getPaymentMethodKeyOrFail()]);

        $paymentMethodCriteriaTransfer = new PaymentMethodCriteriaTransfer();
        $paymentMethodCriteriaTransfer
            ->setPaymentMethodConditions($paymentMethodConditionTransfer);

        $paymentMethodTransfers = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer)->getPaymentMethods();

        if (!$paymentMethodTransfers->offsetExists(0)) {
            return (new PaymentCustomerResponseTransfer())
                ->setIsSuccessful(false)
                ->setError('Payment method not found');
        }

        $paymentMethodTransfer = $paymentMethodTransfers->offsetGet(0);

        $postData = [
            'customerPaymentServiceProviderData' => $paymentCustomerRequestTransfer->getCustomerPaymentServiceProviderData(),
        ];

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod(Request::METHOD_POST)
            ->setUri($this->getCustomerEndpoint($paymentMethodTransfer))
            ->setBody((string)$this->utilEncodingService->encodeJson($postData));

        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        // 200 Created is the expected response and only when we receive this it is successful
        $isSuccessful = $acpHttpResponseTransfer->getHttpStatusCode() === 200;

        $paymentCustomerResponseTransfer = new PaymentCustomerResponseTransfer();
        $paymentCustomerResponseTransfer
            ->setIsSuccessful($isSuccessful);

        $decodedResponseBody = (array)$this->utilEncodingService->decodeJson($acpHttpResponseTransfer->getContentOrFail(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $paymentCustomerResponseTransfer->setError($acpHttpResponseTransfer->getContentOrFail());

            return $paymentCustomerResponseTransfer;
        }

        if (!$isSuccessful) {
            $paymentCustomerResponseTransfer->setError($this->getMessageFromResponse($decodedResponseBody));

            return $paymentCustomerResponseTransfer;
        }

        $customerData = $decodedResponseBody['customer'];
        $shippingAddress = $customerData['shippingAddress'] ?? [];
        $billingAddress = $customerData['billingAddress'] ?? [];

        unset($customerData['shippingAddress'], $customerData['billingAddress']);

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerData, true);

        $customerTransfer->addShippingAddress((new AddressTransfer())->fromArray($shippingAddress, true));
        $customerTransfer->addBillingAddress((new AddressTransfer())->fromArray($billingAddress, true));

        $paymentCustomerResponseTransfer->setCustomer($customerTransfer);

        return $paymentCustomerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function getPaymentMethodTransferFromRequestTransfer(
        PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
    ): PaymentMethodTransfer {
        $paymentTransfer = $paymentCustomerRequestTransfer->getPaymentOrFail();

        $paymentProviderTransfer = new PaymentProviderTransfer();
        $paymentProviderTransfer->setName($paymentTransfer->getPaymentProviderNameOrFail());

        $normalizedPaymentMethodKey = str_replace(' ', '-', mb_strtolower($paymentTransfer->getPaymentMethodNameOrFail()));
        $paymentMethodKey = sprintf('%s-%s', $paymentTransfer->getPaymentProviderNameOrFail(), $normalizedPaymentMethodKey);

        return (new PaymentMethodTransfer())
            ->setName($paymentTransfer->getPaymentMethodNameOrFail())
            ->setPaymentMethodKey($paymentMethodKey)
            ->setPaymentProvider($paymentProviderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @throws \Spryker\Zed\PaymentApp\Business\Exception\PaymentAppEndpointNotFoundException
     *
     * @return string
     */
    protected function getCustomerEndpoint(PaymentMethodTransfer $paymentMethodTransfer): string
    {
        $paymentMethodAppConfigurationTransfer = $paymentMethodTransfer->getPaymentMethodAppConfigurationOrFail();

        foreach ($paymentMethodAppConfigurationTransfer->getEndpoints() as $endpointTransfer) {
            if ($endpointTransfer->getNameOrFail() === PaymentAppConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_CUSTOMER) {
                return sprintf('%s%s', $paymentMethodAppConfigurationTransfer->getBaseUrlOrFail(), $endpointTransfer->getPathOrFail());
            }
        }

        throw new PaymentAppEndpointNotFoundException(sprintf('Could not find an endpoint for getting customers data of the payment method "%s". A reason for this could be that the Payment Apps configuration was not updated or not synced back.', $paymentMethodTransfer->getPaymentMethodKey()));
    }

    /**
     * @param array<mixed> $response
     *
     * @return string
     */
    protected function getMessageFromResponse(array $response): string
    {
        if (isset($response['error'])) {
            return $response['error'];
        }

        if (isset($response[0]['message'])) {
            return $response[0]['message'];
        }

        return 'Response does not contain an error field.';
    }
}
