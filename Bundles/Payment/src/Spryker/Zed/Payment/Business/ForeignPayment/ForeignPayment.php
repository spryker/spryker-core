<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\ForeignPayment;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Payment\PaymentServiceInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Payment\Business\Exception\PreOrderPaymentException;
use Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToKernelAppFacadeInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface;
use Spryker\Zed\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface;
use Spryker\Zed\Payment\PaymentConfig;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ForeignPayment implements ForeignPaymentInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PAYMENT_AUTHORIZATION = 'Payment provider is currently unavailable, please try again later.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PAYMENT_FAILED = 'payment failed';

    /**
     * @var \Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface
     */
    protected QuoteDataMapperInterface $quoteDataMapper;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface
     */
    protected PaymentToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToKernelAppFacadeInterface
     */
    protected PaymentToKernelAppFacadeInterface $kernelAppFacade;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected PaymentRepositoryInterface $paymentRepository;

    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected PaymentConfig $paymentConfig;

    /**
     * @var \Spryker\Service\Payment\PaymentServiceInterface
     */
    protected PaymentServiceInterface $paymentService;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface
     */
    protected PaymentToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\Payment\Business\Mapper\QuoteDataMapperInterface $quoteDataMapper
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToKernelAppFacadeInterface $kernelAppFacade
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Zed\Payment\PaymentConfig $paymentConfig
     * @param \Spryker\Service\Payment\PaymentServiceInterface $paymentService
     * @param \Spryker\Zed\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        QuoteDataMapperInterface $quoteDataMapper,
        PaymentToLocaleFacadeInterface $localeFacade,
        PaymentToKernelAppFacadeInterface $kernelAppFacade,
        PaymentRepositoryInterface $paymentRepository,
        PaymentConfig $paymentConfig,
        PaymentServiceInterface $paymentService,
        PaymentToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->quoteDataMapper = $quoteDataMapper;
        $this->localeFacade = $localeFacade;
        $this->kernelAppFacade = $kernelAppFacade;
        $this->paymentRepository = $paymentRepository;
        $this->paymentConfig = $paymentConfig;
        $this->paymentService = $paymentService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function initializePayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        $paymentSelectionKey = $this->paymentService->getPaymentSelectionKey($quoteTransfer->getPaymentOrFail());

        if ($paymentSelectionKey !== PaymentTransfer::FOREIGN_PAYMENTS || count($quoteTransfer->getPreOrderPaymentData()) > 0) {
            return;
        }

        $paymentMethodKey = $this->paymentService->getPaymentMethodKey($quoteTransfer->getPaymentOrFail());

        $paymentMethodTransfer = $this->paymentRepository->findPaymentMethod(
            (new PaymentMethodTransfer())->setPaymentMethodKey($paymentMethodKey),
        );

        if (!$paymentMethodTransfer || (!$paymentMethodTransfer->getPaymentAuthorizationEndpoint() && !$paymentMethodTransfer->getPaymentMethodAppConfiguration())) {
            return;
        }

        $saveOrderTransfer = $checkoutResponseTransfer->getSaveOrderOrFail();

        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $quoteTransfer->setOrderReference($saveOrderTransfer->getOrderReference());
        $quoteTransfer->getCustomerOrFail()->setLocale($localeTransfer);

        $language = $this->getCurrentLanguage($localeTransfer);

        $postData = [
            'orderData' => $this->quoteDataMapper->mapQuoteDataByAllowedFields(
                $quoteTransfer,
                $this->paymentConfig->getQuoteFieldsForForeignPayment(),
            ),
            'redirectSuccessUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentConfig->getSuccessRoute(),
            ),
            'redirectCancelUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentConfig->getCancelRoute(),
                ['orderReference' => $quoteTransfer->getOrderReference()],
            ),
            'checkoutSummaryPageUrl' => $this->generatePaymentRedirectUrl(
                $language,
                $this->paymentConfig->getCheckoutSummaryPageRoute(),
            ),
        ];

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod(Request::METHOD_POST)
            ->setUri($this->getAuthorizationEndpoint($paymentMethodTransfer))
            ->setBody((string)$this->utilEncodingService->encodeJson($postData));

        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);
        $decodedResponseBody = $this->utilEncodingService->decodeJson($acpHttpResponseTransfer->getContentOrFail(), true);

        if ($acpHttpResponseTransfer->getHttpStatusCode() !== Response::HTTP_OK || json_last_error() !== JSON_ERROR_NONE) {
            $this->logAcpHttpResponseError($acpHttpRequestTransfer, $acpHttpResponseTransfer);
            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($this->createCheckoutErrorTransfer($acpHttpRequestTransfer, $acpHttpResponseTransfer));

            return;
        }

        $paymentAuthorizeResponseTransfer = new PaymentAuthorizeResponseTransfer();
        $paymentAuthorizeResponseTransfer->fromArray((array)$decodedResponseBody, true);

        if (!$paymentAuthorizeResponseTransfer->getIsSuccessful()) {
            $this->logAcpHttpResponseError($acpHttpRequestTransfer, $acpHttpResponseTransfer);

            $checkoutResponseTransfer
                ->setIsSuccess(false)
                ->addError($this->createCheckoutErrorTransfer($acpHttpRequestTransfer, $acpHttpResponseTransfer));

            return;
        }

        if ($this->paymentConfig->getStoreFrontPaymentPage() === '') {
            $checkoutResponseTransfer
                ->setIsExternalRedirect(true)
                ->setRedirectUrl($paymentAuthorizeResponseTransfer->getRedirectUrl());

            return;
        }

        $redirectUrl = $this->addQueryParametersToUrl($this->paymentConfig->getStoreFrontPaymentPage(), [
            'url' => base64_encode($paymentAuthorizeResponseTransfer->getRedirectUrl()),
        ]);

        $checkoutResponseTransfer
            ->setIsExternalRedirect(true)
            ->setRedirectUrl($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function initializePreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        $quoteTransfer = $preOrderPaymentRequestTransfer->getQuoteOrFail();

        $paymentMethodTransfer = $this->paymentRepository->findPaymentMethod(
            $this->getPaymentMethodTransferFromPreOrderPaymentRequestTransfer($preOrderPaymentRequestTransfer),
        );

        if (!$paymentMethodTransfer) {
            return (new PreOrderPaymentResponseTransfer())
                ->setIsSuccessful(false)
                ->setError('Payment method not found');
        }

        try {
            $url = $this->getAuthorizationEndpoint($paymentMethodTransfer);
        } catch (PreOrderPaymentException $e) {
            return (new PreOrderPaymentResponseTransfer())
                ->setIsSuccessful(false)
                ->setError($e->getMessage());
        }

        $postData = [
            'orderData' => $this->quoteDataMapper->mapQuoteDataByAllowedFields(
                $quoteTransfer,
                $this->paymentConfig->getQuoteFieldsForForeignPayment(),
            ),
            'preOrderPaymentData' => $preOrderPaymentRequestTransfer->getPreOrderPaymentData(),
        ];

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod(Request::METHOD_POST)
            ->setUri($url)
            ->setBody((string)$this->utilEncodingService->encodeJson($postData));

        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        // 200 Created is the expected response and only when we receive this it is successful
        $isSuccessful = $acpHttpResponseTransfer->getHttpStatusCode() === 200;

        $initializePreOrderPaymentResponseTransfer = new PreOrderPaymentResponseTransfer();
        $initializePreOrderPaymentResponseTransfer
            ->setIsSuccessful($isSuccessful);

        if (!$isSuccessful) {
            $initializePreOrderPaymentResponseTransfer->setError($acpHttpResponseTransfer->getContent());

            return $initializePreOrderPaymentResponseTransfer;
        }

        $decodedResponseBody = $this->utilEncodingService->decodeJson($acpHttpResponseTransfer->getContentOrFail(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $initializePreOrderPaymentResponseTransfer->setError($acpHttpResponseTransfer->getContentOrFail());

            return $initializePreOrderPaymentResponseTransfer;
        }

        if (!isset($decodedResponseBody[PaymentConfig::PRE_ORDER_PAYMENT_DATA_FIELD])) {
            $initializePreOrderPaymentResponseTransfer->setError('Response does not have the required preOrderPaymentData.');

            return $initializePreOrderPaymentResponseTransfer;
        }

        if (isset($decodedResponseBody['isSuccessful'])) {
            $initializePreOrderPaymentResponseTransfer->setIsSuccessful($decodedResponseBody['isSuccessful']);

            if ($decodedResponseBody['isSuccessful'] === false) {
                $initializePreOrderPaymentResponseTransfer->setError(sprintf('Request to payment provider failed. %s', $decodedResponseBody['message'] ?? ''));

                return $initializePreOrderPaymentResponseTransfer;
            }
        }

        $initializePreOrderPaymentResponseTransfer->setPreOrderPaymentData($decodedResponseBody[PaymentConfig::PRE_ORDER_PAYMENT_DATA_FIELD]);

        return $initializePreOrderPaymentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @throws \Spryker\Zed\Payment\Business\Exception\PreOrderPaymentException
     *
     * @return void
     */
    public function confirmPreOrderPayment(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void {
        if (count($quoteTransfer->getPreOrderPaymentData()) === 0) {
            return;
        }

        $paymentMethodTransfer = $this->paymentRepository->findPaymentMethod(
            (new PaymentMethodTransfer())
                ->setName($quoteTransfer->getPaymentOrFail()->getPaymentMethodOrFail())
                ->setPaymentProvider((new PaymentProviderTransfer())->setName($quoteTransfer->getPaymentOrFail()->getPaymentProviderOrFail())),
        );

        if (!$paymentMethodTransfer || !$paymentMethodTransfer->getPaymentMethodAppConfiguration()) {
            return;
        }

        $saveOrderTransfer = $checkoutResponseTransfer->getSaveOrderOrFail();

        $orderData = $this->quoteDataMapper->mapQuoteDataByAllowedFields(
            $quoteTransfer,
            $this->paymentConfig->getQuoteFieldsForForeignPayment(),
        );

        $requestBody = [
            'orderReference' => $saveOrderTransfer->getOrderReference(),
            'orderData' => $orderData,
            PaymentConfig::PRE_ORDER_PAYMENT_DATA_FIELD => $quoteTransfer->getPreOrderPaymentData(),
        ];

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod(Request::METHOD_POST)
            ->setUri($this->getConfirmPreOrderPaymentEndpoint($paymentMethodTransfer))
            ->setBody((string)$this->utilEncodingService->encodeJson($requestBody));

        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        if ($acpHttpResponseTransfer->getHttpStatusCode() !== Response::HTTP_OK) {
            throw new PreOrderPaymentException(sprintf(
                'Failed to confirm pre-order payment for order %s. Response: %s',
                $saveOrderTransfer->getOrderReference(),
                $acpHttpResponseTransfer->getContent(),
            ));
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    public function cancelPreOrderPayment(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PreOrderPaymentResponseTransfer {
        $paymentMethodTransfer = $this->paymentRepository->findPaymentMethod(
            $this->getPaymentMethodTransferFromPreOrderPaymentRequestTransfer($preOrderPaymentRequestTransfer),
        );

        if (!$paymentMethodTransfer) {
            return (new PreOrderPaymentResponseTransfer())
                ->setIsSuccessful(false)
                ->setError('Payment method not found');
        }

        $postData = [
            'preOrderPaymentData' => $preOrderPaymentRequestTransfer->getPreOrderPaymentData(),
        ];

        $acpHttpRequestTransfer = new AcpHttpRequestTransfer();
        $acpHttpRequestTransfer
            ->setMethod(Request::METHOD_POST)
            ->setUri($this->getCancelPreOrderPaymentEndpoint($paymentMethodTransfer))
            ->setBody((string)$this->utilEncodingService->encodeJson($postData));

        $acpHttpResponseTransfer = $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);

        // 200 Created is the expected response and only when we receive this it is successful
        $isSuccessful = $acpHttpResponseTransfer->getHttpStatusCode() === 200;

        $cancelPreOrderPaymentResponseTransfer = new PreOrderPaymentResponseTransfer();
        $cancelPreOrderPaymentResponseTransfer
            ->setIsSuccessful($isSuccessful);

        $decodedResponseBody = (array)$this->utilEncodingService->decodeJson($acpHttpResponseTransfer->getContentOrFail(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $cancelPreOrderPaymentResponseTransfer->setError($acpHttpResponseTransfer->getContentOrFail());

            return $cancelPreOrderPaymentResponseTransfer;
        }

        if (!$isSuccessful) {
            $cancelPreOrderPaymentResponseTransfer->setError($this->getMessageFromResponse($decodedResponseBody));

            return $cancelPreOrderPaymentResponseTransfer;
        }

        return $cancelPreOrderPaymentResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     * @param \Generated\Shared\Transfer\AcpHttpResponseTransfer $acpHttpResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutErrorTransfer
     */
    protected function createCheckoutErrorTransfer(
        AcpHttpRequestTransfer $acpHttpRequestTransfer,
        AcpHttpResponseTransfer $acpHttpResponseTransfer
    ): CheckoutErrorTransfer {
        return (new CheckoutErrorTransfer())
            ->setErrorCode(static::ERROR_MESSAGE_PAYMENT_FAILED)
            ->setMessage($this->paymentConfig->isDebugEnabled() ? $acpHttpResponseTransfer->getContent() : static::ERROR_MESSAGE_PAYMENT_AUTHORIZATION);
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     * @param \Generated\Shared\Transfer\AcpHttpResponseTransfer $acpHttpResponseTransfer
     *
     * @return void
     */
    protected function logAcpHttpResponseError(
        AcpHttpRequestTransfer $acpHttpRequestTransfer,
        AcpHttpResponseTransfer $acpHttpResponseTransfer
    ): void {
        $this->getLogger()->error(
            static::ERROR_MESSAGE_PAYMENT_FAILED,
            [
                'payment_request' => $acpHttpRequestTransfer->toArray(),
                'response' => $acpHttpResponseTransfer->getContent(),
            ],
        );
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

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @throws \Spryker\Zed\Payment\Business\Exception\PreOrderPaymentException
     *
     * @return string
     */
    protected function getAuthorizationEndpoint(PaymentMethodTransfer $paymentMethodTransfer): string
    {
        $paymentMethodAppConfigurationTransfer = $paymentMethodTransfer->getPaymentMethodAppConfiguration();

        if (!$paymentMethodAppConfigurationTransfer) {
            return $paymentMethodTransfer->getPaymentAuthorizationEndpoint();
        }

        foreach ($paymentMethodAppConfigurationTransfer->getEndpoints() as $endpointTransfer) {
            if ($endpointTransfer->getNameOrFail() === PaymentConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_AUTHORIZATION) {
                return sprintf('%s%s', $paymentMethodAppConfigurationTransfer->getBaseUrlOrFail(), $endpointTransfer->getPathOrFail());
            }
        }

        throw new PreOrderPaymentException(sprintf('Could not find an authorization endpoint for the payment method "%s". A reason for this could be that the Payment Apps configuration was not updated or not synced back.', $paymentMethodTransfer->getPaymentMethodKey()));
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @throws \Spryker\Zed\Payment\Business\Exception\PreOrderPaymentException
     *
     * @return string
     */
    protected function getConfirmPreOrderPaymentEndpoint(PaymentMethodTransfer $paymentMethodTransfer): string
    {
        $paymentMethodAppConfigurationTransfer = $paymentMethodTransfer->getPaymentMethodAppConfiguration();

        foreach ($paymentMethodAppConfigurationTransfer->getEndpoints() as $endpointTransfer) {
            if ($endpointTransfer->getNameOrFail() === PaymentConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_PRE_ORDER_CONFIRMATION) {
                return sprintf('%s%s', $paymentMethodAppConfigurationTransfer->getBaseUrlOrFail(), $endpointTransfer->getPathOrFail());
            }
        }

        throw new PreOrderPaymentException(sprintf('Could not find an endpoint for pre-order payment confirmation of the payment method "%s". A reason for this could be that the Payment Apps configuration was not updated or not synced back.', $paymentMethodTransfer->getPaymentMethodKey()));
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @throws \Spryker\Zed\Payment\Business\Exception\PreOrderPaymentException
     *
     * @return string
     */
    protected function getCancelPreOrderPaymentEndpoint(PaymentMethodTransfer $paymentMethodTransfer): string
    {
        $paymentMethodAppConfigurationTransfer = $paymentMethodTransfer->getPaymentMethodAppConfiguration();

        foreach ($paymentMethodAppConfigurationTransfer->getEndpoints() as $endpointTransfer) {
            if ($endpointTransfer->getNameOrFail() === PaymentConfig::PAYMENT_SERVICE_PROVIDER_ENDPOINT_NAME_PRE_ORDER_CANCELLATION) {
                return sprintf('%s%s', $paymentMethodAppConfigurationTransfer->getBaseUrlOrFail(), $endpointTransfer->getPathOrFail());
            }
        }

        throw new PreOrderPaymentException(sprintf('Could not find an endpoint for pre-order payment cancellation of the payment method "%s". A reason for this could be that the Payment Apps configuration was not updated or not synced back.', $paymentMethodTransfer->getPaymentMethodKey()));
    }

    /**
     * @param string $language
     * @param string $urlPath
     * @param array<string, mixed> $queryParts
     *
     * @return string
     */
    protected function generatePaymentRedirectUrl(string $language, string $urlPath, array $queryParts = []): string
    {
        if ($this->isAbsoluteUrl($urlPath)) {
            return $this->addQueryParametersToUrl($urlPath, $queryParts);
        }

        $url = sprintf(
            '%s/%s%s',
            $this->paymentConfig->getBaseUrlYves(),
            $language,
            $urlPath,
        );

        return Url::generate($url, $queryParts)->build();
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function isAbsoluteUrl(string $url): bool
    {
        $urlComponents = parse_url($url);

        return isset($urlComponents['host']);
    }

    /**
     * @param string $url
     * @param array<string, mixed> $queryParams
     *
     * @return string
     */
    protected function addQueryParametersToUrl(string $url, array $queryParams): string
    {
        if ($queryParams === []) {
            return $url;
        }

        $urlComponents = parse_url($url);

        $url .= isset($urlComponents['query']) ? '&' : '?';
        $url .= http_build_query($queryParams);

        return $url;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getCurrentLanguage(LocaleTransfer $localeTransfer): string
    {
        $splitLocale = explode('_', $localeTransfer->getLocaleNameOrFail());

        return $splitLocale[0];
    }

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function getPaymentMethodTransferFromPreOrderPaymentRequestTransfer(
        PreOrderPaymentRequestTransfer $preOrderPaymentRequestTransfer
    ): PaymentMethodTransfer {
        $paymentTransfer = $preOrderPaymentRequestTransfer->getPaymentOrFail();
        $paymentProviderTransfer = new PaymentProviderTransfer();
        $paymentProviderTransfer->setName($paymentTransfer->getPaymentProviderOrFail());

        return (new PaymentMethodTransfer())
            ->setName($paymentTransfer->getPaymentMethodOrFail())
            ->setPaymentProvider($paymentProviderTransfer);
    }
}
