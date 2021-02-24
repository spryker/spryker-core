<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestCurrencyTransfer;
use Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer;
use Spryker\Glue\ProductOfferPricesRestApi\ProductOfferPricesRestApiConfig;

class ProductOfferPriceMapper implements ProductOfferPriceMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApiExtension\Dependency\Plugin\RestProductOfferPricesAttributesMapperPluginInterface[]
     */
    protected $restProductOfferPricesAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\ProductOfferPricesRestApiExtension\Dependency\Plugin\RestProductOfferPricesAttributesMapperPluginInterface[] $restProductOfferPricesAttributesMapperPlugins
     */
    public function __construct(array $restProductOfferPricesAttributesMapperPlugins)
    {
        $this->restProductOfferPricesAttributesMapperPlugins = $restProductOfferPricesAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer
     */
    public function mapCurrentProductPriceTransferToRestProductOfferPricesAttributesTransfer(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
    ): RestProductOfferPricesAttributesTransfer {
        $restProductOfferPricesAttributesTransfer->setPrice($currentProductPriceTransfer->getPrice());
        $restCurrencyTransfer = (new RestCurrencyTransfer())
            ->fromArray($currentProductPriceTransfer->getCurrencyOrFail()->toArray(), true);
        foreach ($currentProductPriceTransfer->getPrices() as $priceType => $amount) {
            $restProductOfferPriceAttributesTransfer = $this->createRestProductOfferPriceAttributesTransfer(
                $priceType,
                $amount,
                $currentProductPriceTransfer->getPriceModeOrFail(),
                $restCurrencyTransfer
            );
            $restProductOfferPricesAttributesTransfer->addPrice($restProductOfferPriceAttributesTransfer);

            $restProductOfferPricesAttributesTransfer = $this->executeRestProductOfferPricesAttributesMapperPlugins(
                $currentProductPriceTransfer,
                $restProductOfferPricesAttributesTransfer
            );
        }

        return $restProductOfferPricesAttributesTransfer;
    }

    /**
     * @param string $priceType
     * @param int $amount
     * @param string $currentPriceMode
     * @param \Generated\Shared\Transfer\RestCurrencyTransfer $restCurrencyTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferPriceAttributesTransfer
     */
    protected function createRestProductOfferPriceAttributesTransfer(
        string $priceType,
        int $amount,
        string $currentPriceMode,
        RestCurrencyTransfer $restCurrencyTransfer
    ): RestProductOfferPriceAttributesTransfer {
        $restProductOfferPriceAttributesTransfer = (new RestProductOfferPriceAttributesTransfer())
            ->setPriceTypeName($priceType)
            ->setCurrency($restCurrencyTransfer);

        if ($currentPriceMode === ProductOfferPricesRestApiConfig::PRICE_MODE_GROSS) {
            $restProductOfferPriceAttributesTransfer->setGrossAmount($amount);

            return $restProductOfferPriceAttributesTransfer;
        }
        if ($currentPriceMode === ProductOfferPricesRestApiConfig::PRICE_MODE_NET) {
            $restProductOfferPriceAttributesTransfer->setNetAmount($amount);

            return $restProductOfferPriceAttributesTransfer;
        }

        return $restProductOfferPriceAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer
     */
    public function executeRestProductOfferPricesAttributesMapperPlugins(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
    ): RestProductOfferPricesAttributesTransfer {
        foreach ($this->restProductOfferPricesAttributesMapperPlugins as $restProductOfferPricesAttributesMapperPlugin) {
            $restProductOfferPricesAttributesTransfer = $restProductOfferPricesAttributesMapperPlugin->map(
                $currentProductPriceTransfer,
                $restProductOfferPricesAttributesTransfer
            );
        }

        return $restProductOfferPricesAttributesTransfer;
    }
}
