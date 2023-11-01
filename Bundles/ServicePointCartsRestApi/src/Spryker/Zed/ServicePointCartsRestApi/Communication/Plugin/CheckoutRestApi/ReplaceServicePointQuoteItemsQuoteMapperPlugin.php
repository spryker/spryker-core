<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartsRestApi\Communication\Plugin\CheckoutRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\CheckoutRestApiExtension\Dependency\Plugin\QuoteMapperPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ServicePointCartsRestApi\Business\ServicePointCartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiConfig getConfig()
 */
class ReplaceServicePointQuoteItemsQuoteMapperPlugin extends AbstractPlugin implements QuoteMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Replaces quote items using applicable strategy if shipments are provided.
     * - Returns original `QuoteTransfer` in case `RestCheckoutRequestAttributesTransfer.shipments` and `RestCheckoutRequestAttributesTransfer.shipment` are not provided.
     * - Returns replaced and recalculated quote items if a replacement strategy executed successfully.
     * - Returns the result of a replacement strategy without recalculation if the replacement strategy executed with any error.
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
        return $this->getFacade()->replaceServicePointQuoteItems($restCheckoutRequestAttributesTransfer, $quoteTransfer);
    }
}
