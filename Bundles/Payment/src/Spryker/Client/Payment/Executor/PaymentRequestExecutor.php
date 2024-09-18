<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment\Executor;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface;
use Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface;
use Spryker\Client\Payment\Http\Exception\PaymentHttpRequestException;
use Spryker\Client\Payment\PaymentConfig;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentRequestExecutor implements PaymentRequestExecutorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    public const MESSAGE_ERROR_PAYMENT_AUTHORIZATION = 'Payment provider is currently unavailable, please try again later.';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_LOG_PAYMENT_AUTHORIZATION = 'Something went wrong with your payment.';

    /**
     * @var \Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Client\Payment\PaymentConfig
     */
    protected PaymentConfig $config;

    /**
     * @param \Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface $httpClient
     * @param \Spryker\Client\Payment\PaymentConfig $config
     */
    public function __construct(
        PaymentToUtilEncodingServiceInterface $utilEncodingService,
        PaymentToHttpClientAdapterInterface $httpClient,
        PaymentConfig $config
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer
     */
    public function authorizeForeignPayment(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeResponseTransfer {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_POST,
                $paymentAuthorizeRequestTransfer->getRequestUrlOrFail(),
                [
                    RequestOptions::FORM_PARAMS => $paymentAuthorizeRequestTransfer->getPostData(),
                    RequestOptions::HEADERS => $this->getRequestHeaders($paymentAuthorizeRequestTransfer),
                ],
            );
        } catch (PaymentHttpRequestException $e) {
            return $this->getFailedPaymentAuthorizeResponse($paymentAuthorizeRequestTransfer, $e->getResponse());
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return $this->getFailedPaymentAuthorizeResponse($paymentAuthorizeRequestTransfer, $response);
        }

        $responseData = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);
        $paymentAuthorizeResponseTransfer = (new PaymentAuthorizeResponseTransfer())->fromArray($responseData, true);
        $this->getLogger()->error(
            static::MESSAGE_ERROR_LOG_PAYMENT_AUTHORIZATION,
            [
                'payment_request' => $paymentAuthorizeRequestTransfer->toArray(),
                'response' => $paymentAuthorizeResponseTransfer->toArray(),
            ],
        );

        if ($paymentAuthorizeResponseTransfer->getIsSuccessful() === false && !$this->config->isDebugEnabled()) {
            $paymentAuthorizeResponseTransfer->setMessage(static::MESSAGE_ERROR_PAYMENT_AUTHORIZATION);
        }

        return $paymentAuthorizeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     * @param \Psr\Http\Message\ResponseInterface|null $response
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer
     */
    protected function getFailedPaymentAuthorizeResponse(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer,
        ?ResponseInterface $response
    ): PaymentAuthorizeResponseTransfer {
        return (new PaymentAuthorizeResponseTransfer())
            ->setIsSuccessful(false)
            ->setMessage($this->getPaymentAuthorizationErrorMessage($paymentAuthorizeRequestTransfer, $response));
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     * @param \Psr\Http\Message\ResponseInterface|null $response
     *
     * @return string
     */
    protected function getPaymentAuthorizationErrorMessage(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer,
        ?ResponseInterface $response
    ): string {
        $this->getLogger()->error(
            static::MESSAGE_ERROR_LOG_PAYMENT_AUTHORIZATION,
            [
                'payment_request' => $paymentAuthorizeRequestTransfer->toArray(),
                'response' => $response ? $response->getBody()->getContents() : null,
            ],
        );

        if (!$response || !$this->config->isDebugEnabled()) {
            return static::MESSAGE_ERROR_PAYMENT_AUTHORIZATION;
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return array<string, string>
     */
    protected function getRequestHeaders(PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer): array
    {
        $requestHeaders = [
            'Accept' => 'application/json',
        ];

        if ($paymentAuthorizeRequestTransfer->getAuthorization()) {
            $requestHeaders['Authorization'] = $paymentAuthorizeRequestTransfer->getAuthorizationOrFail();
        }

        if ($paymentAuthorizeRequestTransfer->getTenantIdentifier()) {
            $requestHeaders['X-Tenant-Identifier'] = $paymentAuthorizeRequestTransfer->getTenantIdentifierOrFail();
            $requestHeaders['X-Store-Reference'] = $paymentAuthorizeRequestTransfer->getTenantIdentifierOrFail();
        }

        if ($paymentAuthorizeRequestTransfer->getStoreReference()) {
            $requestHeaders['X-Store-Reference'] = $paymentAuthorizeRequestTransfer->getStoreReferenceOrFail();
        }

        return $requestHeaders;
    }
}
