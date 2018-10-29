<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Currency;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class CurrencyValidator implements CurrencyValidatorInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface $currencyClient
     */
    public function __construct(ProductPricesRestApiToCurrencyClientInterface $currencyClient)
    {
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        $currencyIsoCode = $this->getRequestParameter($restRequest, ProductPricesRestApiConfig::REQUEST_PARAMETER_CURRENCY);
        if ($currencyIsoCode === '') {
            return null;
        }

        $currencyTransfer = $this->currencyClient->fromIsoCode($currencyIsoCode);

        if ($currencyTransfer->getSymbol()) {
            return null;
        }

        return (new RestErrorMessageTransfer())
            ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_CURRENCY_NOT_FOUND)
            ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_CURRENCY_NOT_FOUND)
            ->setStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $parameterName
     *
     * @return string
     */
    protected function getRequestParameter(RestRequestInterface $restRequest, string $parameterName): string
    {
        return $restRequest->getHttpRequest()->query->get($parameterName, '');
    }
}
