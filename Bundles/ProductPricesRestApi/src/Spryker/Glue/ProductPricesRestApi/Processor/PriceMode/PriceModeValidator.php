<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\PriceMode;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class PriceModeValidator implements PriceModeValidatorInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface $priceClient
     */
    public function __construct(ProductPricesRestApiToPriceClientInterface $priceClient)
    {
        $this->priceClient = $priceClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorMessageTransfer
    {
        $priceMode = $this->getRequestParameter($restRequest, ProductPricesRestApiConfig::REQUEST_PARAMETER_PRICE_MODE);
        if ($priceMode === '') {
            return null;
        }

        if (in_array($priceMode, $this->priceClient->getPriceModes())) {
            return null;
        }

        return (new RestErrorMessageTransfer())
            ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_PRICE_MODE_NOT_FOUND)
            ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_PRICE_MODE_NOT_FOUND)
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
