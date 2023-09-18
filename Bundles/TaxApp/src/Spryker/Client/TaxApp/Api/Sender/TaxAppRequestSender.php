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
use GuzzleHttp\RequestOptions;
use Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilderInterface;
use Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface;
use Spryker\Client\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface;
use Spryker\Client\TaxApp\Exception\TaxCalculationResponseException;
use Spryker\Shared\Log\LoggerTrait;
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
    protected const MESSAGE_REQUEST_FAILED = 'Failed to request Tax Quotation with message: `%s`';

    /**
     * @var \Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilderInterface
     */
    protected TaxAppHeaderBuilderInterface $taxAppHeaderBuilder;

    /**
     * @var \Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface
     */
    protected TaxAppToHttpClientAdapterInterface $httpClient;

    /**
     * @var \Spryker\Client\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface
     */
    protected TaxAppToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Client\TaxApp\Api\Builder\TaxAppHeaderBuilderInterface $taxAppHeaderBuilder
     * @param \Spryker\Client\TaxApp\Dependency\External\TaxAppToHttpClientAdapterInterface $httpClient
     * @param \Spryker\Client\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        TaxAppHeaderBuilderInterface $taxAppHeaderBuilder,
        TaxAppToHttpClientAdapterInterface $httpClient,
        TaxAppToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->taxAppHeaderBuilder = $taxAppHeaderBuilder;
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\TaxCalculationRequestTransfer $taxCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\TaxAppConfigTransfer $taxAppConfigTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @throws \Spryker\Client\TaxApp\Exception\TaxCalculationResponseException
     *
     * @return \Generated\Shared\Transfer\TaxCalculationResponseTransfer
     */
    public function requestTaxQuotation(
        TaxCalculationRequestTransfer $taxCalculationRequestTransfer,
        TaxAppConfigTransfer $taxAppConfigTransfer,
        StoreTransfer $storeTransfer
    ): TaxCalculationResponseTransfer {
        $taxCalculationResponseTransfer = new TaxCalculationResponseTransfer();

        try {
            $taxCalculationRequestArray = $taxCalculationRequestTransfer->toArray(true, true);
            unset($taxCalculationRequestArray['Authorization']);
            $requestBody = $this->utilEncodingService->encodeJson($taxCalculationRequestArray);

            $httpResponse = $this->httpClient->request(
                Request::METHOD_POST,
                $taxAppConfigTransfer->getApiUrlOrFail(),
                [
                    RequestOptions::HEADERS => $this->taxAppHeaderBuilder->build($taxCalculationRequestTransfer, $storeTransfer),
                    RequestOptions::BODY => $requestBody,
                ],
            );

            if ($httpResponse->getStatusCode() !== 200) {
                throw new TaxCalculationResponseException(sprintf(static::MESSAGE_UNEXPECTED_STATUS_CODE, $httpResponse->getStatusCode()));
            }

            $responseData = $this->utilEncodingService->decodeJson($httpResponse->getBody()->getContents(), true);

            if (!is_array($responseData)) {
                throw new TaxCalculationResponseException(static::MESSAGE_INVALID_RESPONSE);
            }

            $taxCalculationResponseTransfer = $taxCalculationResponseTransfer->fromArray($responseData, true);
            $taxCalculationResponseTransfer->setIsSuccessful(true);
        } catch (Throwable | Exception $e) {
            $errorTransfer = (new ApiErrorMessageTransfer())->setCode($e->getCode())->setDetail($e->getMessage());
            $taxCalculationResponseTransfer->setIsSuccessful(false);
            $taxCalculationResponseTransfer->addApiErrorMessage($errorTransfer);

            $this->getLogger()->error(sprintf(static::MESSAGE_REQUEST_FAILED, $e->getMessage()), ['exception' => $e]);
        }

        return $taxCalculationResponseTransfer;
    }
}
