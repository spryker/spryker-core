<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Builder;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;

class PriceProductFilterTransferBuilder implements PriceProductFilterTransferBuilderInterface
{
 /**
  * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface
  */
    protected ProductPricesRestApiToCurrencyClientInterface $currencyClient;

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
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function build(RestRequestInterface $restRequest): PriceProductFilterTransfer
    {
        $priceProductFilterTransfer = (new PriceProductFilterTransfer());

        $currencyIsoCode = $this->getRequestParameter($restRequest, ProductPricesRestApiConfig::REQUEST_PARAMETER_CURRENCY);

        if ($currencyIsoCode === null || !$this->isValidCurrencyIsoCode($currencyIsoCode)) {
            return $priceProductFilterTransfer
                ->setCurrency($this->currencyClient->getCurrent());
        }

        $priceProductFilterTransfer
            ->setCurrency($this->currencyClient->fromIsoCode($currencyIsoCode))
            ->setCurrencyIsoCode($currencyIsoCode);

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $parameterName
     *
     * @return string|null
     */
    protected function getRequestParameter(RestRequestInterface $restRequest, string $parameterName): ?string
    {
        /**
         * @var string|null $value
         */
        $value = $restRequest->getHttpRequest()->query->get($parameterName, null);

        return $value;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return bool
     */
    protected function isValidCurrencyIsoCode(string $currencyIsoCode): bool
    {
        return in_array($currencyIsoCode, $this->currencyClient->getCurrencyIsoCodes());
    }
}
