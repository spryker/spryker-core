<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Currency;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToStoreClientInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class CurrencyValidator implements CurrencyValidatorInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface $currencyClient
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToStoreClientInterface $storeClient
     */
    public function __construct(
        ProductPricesRestApiToCurrencyClientInterface $currencyClient,
        ProductPricesRestApiToStoreClientInterface $storeClient
    ) {
        $this->currencyClient = $currencyClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $currencyIsoCode = $this->getRequestParameter($restRequest, ProductPricesRestApiConfig::REQUEST_PARAMETER_CURRENCY);

        if (empty($currencyIsoCode)) {
            return null;
        }

        $currencyIsoCodes = $this->storeClient->getCurrentStore()->getAvailableCurrencyIsoCodes();

        if (in_array($currencyIsoCode, $currencyIsoCodes)) {
            return null;
        }

        return (new RestErrorCollectionTransfer())->addRestError(
            (new RestErrorMessageTransfer())
                ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_INVALID_CURRENCY)
                ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_INVALID_CURRENCY)
                ->setStatus(Response::HTTP_BAD_REQUEST)
        );
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
