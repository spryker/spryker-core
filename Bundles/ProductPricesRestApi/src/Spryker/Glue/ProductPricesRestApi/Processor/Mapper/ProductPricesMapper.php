<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface;

class ProductPricesMapper implements ProductPricesMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface $priceClient
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        ProductPricesRestApiToPriceClientInterface $priceClient,
        ProductPricesRestApiToCurrencyClientInterface $currencyClient
    ) {
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapCurrentProductPriceTransferToRestProductPricesAttributesTransfer(
        CurrentProductPriceTransfer $currentProductPriceTransfer
    ): RestProductPricesAttributesTransfer {
        /** @todo: This mapping should be changed after decision about price filtering in api. */
        $productPricesRestAttributesTransfer = (new RestProductPricesAttributesTransfer())
            ->setPrice($currentProductPriceTransfer->getPrice());
        foreach ($currentProductPriceTransfer->getPrices() as $priceType => $amount) {
            $restProductPriceAttributesTransfer = $this->getRestProductPriceAttributesTransfer($priceType, $amount);
            $productPricesRestAttributesTransfer->addPrice($restProductPriceAttributesTransfer);
        }

        return $productPricesRestAttributesTransfer;
    }

    /**
     * @param string $priceType
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    protected function getRestProductPriceAttributesTransfer(string $priceType, int $amount): RestProductPriceAttributesTransfer
    {
        $restProductPriceAttributesTransfer = new RestProductPriceAttributesTransfer();

        $restProductPriceAttributesTransfer->setPriceTypeName($priceType);
        $restProductPriceAttributesTransfer->setCurrency($this->getRestCurrencyTransfer());
        if ($this->priceClient->getCurrentPriceMode() === $this->priceClient->getGrossPriceModeIdentifier()) {
            $restProductPriceAttributesTransfer->setGrossAmount($amount);

            return $restProductPriceAttributesTransfer;
        }
        if ($this->priceClient->getCurrentPriceMode() === $this->priceClient->getNetPriceModeIdentifier()) {
            $restProductPriceAttributesTransfer->setNetAmount($amount);

            return $restProductPriceAttributesTransfer;
        }

        return $restProductPriceAttributesTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestCurrencyTransfer
     */
    protected function getRestCurrencyTransfer(): RestCurrencyTransfer
    {
        return (new RestCurrencyTransfer())
            ->fromArray($this->currencyClient->getCurrent()->toArray(), true);
    }
}
