<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductOfferPricesAttributesTransfer;

/**
 * Use this plugin to map additional data from `CurrentProductPriceTransfer` to `RestProductOfferPricesAttributesTransfer`.
 */
interface RestProductOfferPricesAttributesMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `CurrentProductPrice` transfer object to `RestProductOfferPricesAttributes` transfer object.
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
    ): RestProductOfferPricesAttributesTransfer;
}
