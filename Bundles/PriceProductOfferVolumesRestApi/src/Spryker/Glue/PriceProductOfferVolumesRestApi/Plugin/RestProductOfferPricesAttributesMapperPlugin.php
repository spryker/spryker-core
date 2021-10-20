<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductOfferVolumesRestApi\Plugin;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductOfferPricesRestApiExtension\Dependency\Plugin\RestProductOfferPricesAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\PriceProductOfferVolumesRestApi\PriceProductOfferVolumesRestApiFactory getFactory()
 */
class RestProductOfferPricesAttributesMapperPlugin extends AbstractPlugin implements RestProductOfferPricesAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps the `CurrentProductPrice.priceData` to `RestProductOfferPricesAttributes.volumePrices`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer
     */
    public function map(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductOfferPricesAttributesTransfer $restProductOfferPricesAttributesTransfer
    ): RestProductOfferPricesAttributesTransfer {
        return $this->getFactory()
            ->createRestProductOfferPricesAttributesMapper()
            ->mapCurrentProductPriceTransferToRestProductOfferPricesAttributesTransfer(
                $currentProductPriceTransfer,
                $restProductOfferPricesAttributesTransfer,
            );
    }
}
