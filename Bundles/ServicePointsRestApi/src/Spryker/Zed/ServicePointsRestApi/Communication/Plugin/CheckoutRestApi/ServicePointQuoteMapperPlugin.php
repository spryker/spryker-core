<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ServicePointsRestApi\Business\ServicePointsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointsRestApi\ServicePointsRestApiConfig getConfig()
 */
class ServicePointQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `RestCheckoutRequestAttributesTransfer.servicePoints` is not provided.
     * - Requires `QuoteTransfer.store.name` and `RestCheckoutRequestAttributesTransfer.servicePoints.idServicePoint` to be provided.
     * - Gets service points collection by `RestCheckoutRequestAttributesTransfer.servicePoints.idServicePoint`.
     * - Maps found filtered `ServicePointTransfers` to `QuoteTransfer.items.servicePoint`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function map(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        return $this->getFacade()->mapServicePointToQuoteItem($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
