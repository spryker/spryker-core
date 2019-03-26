<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\PriceMode;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
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
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer|null
     */
    public function validate(RestRequestInterface $restRequest): ?RestErrorCollectionTransfer
    {
        $priceMode = $this->getRequestParameter($restRequest, ProductPricesRestApiConfig::REQUEST_PARAMETER_PRICE_MODE);

        if (empty($priceMode)) {
            return null;
        }

        if (in_array($priceMode, $this->priceClient->getPriceModes())) {
            return null;
        }

        return (new RestErrorCollectionTransfer())->addRestError(
            (new RestErrorMessageTransfer())
                ->setDetail(ProductPricesRestApiConfig::RESPONSE_DETAILS_INVALID_PRICE_MODE)
                ->setCode(ProductPricesRestApiConfig::RESPONSE_CODE_INVALID_PRICE_MODE)
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
