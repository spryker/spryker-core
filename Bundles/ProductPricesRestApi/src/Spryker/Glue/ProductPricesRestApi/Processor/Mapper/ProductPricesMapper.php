<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface;

class ProductPricesMapper implements ProductPricesMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Dependency\Client\ProductPricesRestApiToPriceClientInterface $priceClient
     */
    public function __construct(
        ProductPricesRestApiToPriceClientInterface $priceClient
    ) {
        $this->priceClient = $priceClient;
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
            $restProductPriceAttributesTransfer = (new RestProductPriceAttributesTransfer())
                ->setPriceTypeName($priceType);
            if ($this->priceClient->getCurrentPriceMode() === $this->priceClient->getGrossPriceModeIdentifier()) {
                $restProductPriceAttributesTransfer->setGrossAmount($amount);
            }
            if ($this->priceClient->getCurrentPriceMode() === $this->priceClient->getNetPriceModeIdentifier()) {
                $restProductPriceAttributesTransfer->setNetAmount($amount);
            }
            $productPricesRestAttributesTransfer->addPrice($restProductPriceAttributesTransfer);
        }

        return $productPricesRestAttributesTransfer;
    }
}
