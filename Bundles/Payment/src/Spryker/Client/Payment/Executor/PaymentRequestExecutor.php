<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment\Executor;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use GuzzleHttp\RequestOptions;
use Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface;
use Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface;
use Spryker\Client\Payment\Http\Exception\PaymentHttpRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentRequestExecutor implements PaymentRequestExecutorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_ERROR_PAYMENT_AUTHORIZATION = 'Something went wrong with your payment.';

    /**
     * @var \Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface
     */
    protected $httpClient;

    /**
     * @param \Spryker\Client\Payment\Dependency\Service\PaymentToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\Payment\Dependency\External\PaymentToHttpClientAdapterInterface $httpClient
     */
    public function __construct(
        PaymentToUtilEncodingServiceInterface $utilEncodingService,
        PaymentToHttpClientAdapterInterface $httpClient
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->httpClient = $httpClient;
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
            return (new PaymentAuthorizeResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessage(static::MESSAGE_ERROR_PAYMENT_AUTHORIZATION);
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return (new PaymentAuthorizeResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessage(static::MESSAGE_ERROR_PAYMENT_AUTHORIZATION);
        }

        $responseData = $this->utilEncodingService->decodeJson($response->getBody()->getContents(), true);

        return (new PaymentAuthorizeResponseTransfer())->fromArray($responseData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return array<string, string>
     */
    protected function getRequestHeaders(PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer): array
    {
        $requestHeaders = [];

        if ($paymentAuthorizeRequestTransfer->getAuthorization()) {
            $requestHeaders['Authorization'] = $paymentAuthorizeRequestTransfer->getAuthorizationOrFail();
        }

        if ($paymentAuthorizeRequestTransfer->getStoreReference()) {
            $requestHeaders['X-Store-Reference'] = $paymentAuthorizeRequestTransfer->getStoreReferenceOrFail();
        }

        return $requestHeaders;
    }
}
