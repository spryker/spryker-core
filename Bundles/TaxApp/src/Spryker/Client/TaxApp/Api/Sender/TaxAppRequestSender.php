<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\TaxApp\Api\Sender;

use Exception;
use Generated\Shared\Transfer\ApiErrorMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Generated\Shared\Transfer\TaxCalculationRequestTransfer;
use Generated\Shared\Transfer\TaxCalculationResponseTransfer;
use Generated\Shared\Transfer\TaxRefundRequestTransfer;
use GuzzleHttp\RequestOptions;
use Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilderInterface;
use Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface;
use Spryker\Client\TaxApp\Exception\TaxAppInvalidConfigException;
use Spryker\Client\TaxApp\Exception\TaxCalculationResponseException;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class TaxAppRequestSender implements TaxAppRequestSenderInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const MESSAGE_UNEXPECTED_STATUS_CODE = 'Response status code expected to be 200, received `%s` instead.';

    /**
     * @var string
     */
    protected const MESSAGE_INVALID_RESPONSE = 'Response data is invalid.';

    /**
     * @var string
     */
    protected const MESSAGE_REQUEST_FAILED = 'Failed to execute Tax Request with message: `%s`';

    /**
     * @var \Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilderInterface
     */
    protected TaxAppHeaderBuilderInterface $taxAppHeaderBuilder;

    /**
     * @var \Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface
     */
    protected TaxAppToHttpClientAdapterInterface $httpClient;

    /**
     * @var \Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface
     */
    protected TaxAppToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var int
     */
    protected int $requestTimeoutSeconds;

    /**
     * @param \Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilderInterface $taxAppHeaderBuilder
     * @param \Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface $httpClient
     * @param \Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface $utilEncodingService
     * @param int $requestTimeoutSeconds
     */
    public function __construct(
        TaxAppHeaderBuilderInterface $taxAppHeaderBuilder,
        TaxAppToHttpClientAdapterInterface $httpClient,
        TaxAppToUtilEncodingServiceInterface $utilEncodingService,
        int $requestTimeoutSeconds
    ) {
        $this->taxAppHeaderBuilder = $taxAppHeaderBuilder;
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->requestTimeoutSeconds = $requestTimeoutSeconds;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer $taxCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    public function requestTaxQuotation(
        TaxCalculationRequestTransfer $taxCalculationRequestTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): TaxCalculationResponseTransfer {
        $taxCalculationRequestArray = $taxCalculationRequestTransfer->toArray(true, true);
        unset($taxCalculationRequestArray['authorization']);

        try {
            $taxAppConfigTransfer->getApiUrlsOrFail()->requireQuotationUrl();
            $headers = $this->taxAppHeaderBuilder->build($taxCalculationRequestTransfer, $storeTransfer, $taxAppConfigTransfer);
        } catch (RequiredTransferPropertyException $e) {
            return $this->getErrorMessage((new TaxAppInvalidConfigException('Invalid config: quotation URL is missing', 0, $e)), new TaxCalculationResponseTransfer());
        } catch (TaxAppInvalidConfigException $e) {
            return $this->getErrorMessage($e, new TaxCalculationResponseTransfer());
        }

        return $this->doRequest(
            $taxAppConfigTransfer->getApiUrlsOrFail()->getQuotationUrlOrFail(),
            $taxCalculationRequestArray,
            $headers,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRefundRequestTransfer $taxRefundRequestTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    public function requestTaxRefund(
        TaxRefundRequestTransfer $taxRefundRequestTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): TaxCalculationResponseTransfer {
        $taxRefundRequestArray = $taxRefundRequestTransfer->toArray(true, true);
        unset($taxRefundRequestArray['authorization']);

        try {
            $taxAppConfigTransfer->getApiUrlsOrFail()->requireRefundsUrl();
            $headers = $this->taxAppHeaderBuilder->build($taxRefundRequestTransfer, $storeTransfer, $taxAppConfigTransfer);
        } catch (RequiredTransferPropertyException $e) {
            return $this->getErrorMessage((new TaxAppInvalidConfigException('Invalid config: refund URL is missing', 0, $e)), new TaxCalculationResponseTransfer());
        } catch (TaxAppInvalidConfigException $e) {
            return $this->getErrorMessage($e, new TaxCalculationResponseTransfer());
        }

        return $this->doRequest(
            $taxAppConfigTransfer->getApiUrlsOrFail()->getRefundsUrlOrFail(),
            $taxRefundRequestArray,
            $headers,
        );
    }

    /**
     * @param string $apiUrl
     * @param array<string, mixed> $requestBody
     * @param array<string, mixed> $requestHeaders
     *
     * @throws \Spryker\Client\TaxApp\Exception\TaxCalculationResponseException
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    protected function doRequest(string $apiUrl, array $requestBody, array $requestHeaders): TaxCalculationResponseTransfer
    {
        $taxCalculationResponseTransfer = new TaxCalculationResponseTransfer();

        try {
            $requestBody = $this->utilEncodingService->encodeJson($requestBody);

            $httpResponse = $this->httpClient->request(
                Request::METHOD_POST,
                $apiUrl,
                [
                    RequestOptions::HEADERS => $requestHeaders,
                    RequestOptions::BODY => $requestBody,
                    RequestOptions::TIMEOUT => $this->requestTimeoutSeconds,
                ],
            );

            $responseData = $this->utilEncodingService->decodeJson($httpResponse->getBody()->getContents(), true);

            if (!is_array($responseData)) {
                throw new TaxCalculationResponseException(static::MESSAGE_INVALID_RESPONSE);
            }

            $taxCalculationResponseTransfer = $taxCalculationResponseTransfer->fromArray($responseData, true);
            $taxCalculationResponseTransfer->setIsSuccessful(true);
        } catch (Throwable | Exception $e) {
            $taxCalculationResponseTransfer = $this->getErrorMessage($e, $taxCalculationResponseTransfer);
        }

        return $taxCalculationResponseTransfer;
    }

    /**
     * @param \Throwable|\Exception $e
     * @param \Generated\Shared\Transfer\TaxCalculationResponseTransfer $taxCalculationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    protected function getErrorMessage(Throwable|Exception $e, TaxCalculationResponseTransfer $taxCalculationResponseTransfer): TaxCalculationResponseTransfer
    {
        $errorTransfer = (new ApiErrorMessageTransfer())->setCode($e->getCode())->setDetail($e->getMessage());
        $taxCalculationResponseTransfer->setIsSuccessful(false);
        $taxCalculationResponseTransfer->addApiErrorMessage($errorTransfer);

        $this->getLogger()->error(sprintf(static::MESSAGE_REQUEST_FAILED, $e->getMessage()), ['exception' => $e]);

        return $taxCalculationResponseTransfer;
    }
}
