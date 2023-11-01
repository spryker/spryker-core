<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartsRestApi\Business\Replacer;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeInterface;

class ServicePointQuoteItemReplacer implements ServicePointQuoteItemReplacerInterface
{
    /**
     * @var \Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeInterface
     */
    protected ServicePointCartsRestApiToServicePointCartFacadeInterface $servicePointCartFacade;

    /**
     * @param \Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeInterface $servicePointCartFacade
     */
    public function __construct(ServicePointCartsRestApiToServicePointCartFacadeInterface $servicePointCartFacade)
    {
        $this->servicePointCartFacade = $servicePointCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function replaceServicePointQuoteItems(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        QuoteTransfer $quoteTransfer
    ): QuoteTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\RestShipmentsTransfer> $restShipmentsTransfers */
        $restShipmentsTransfers = $restCheckoutRequestAttributesTransfer->getShipments();

        if (!$restShipmentsTransfers->count() && !$restCheckoutRequestAttributesTransfer->getShipment()) {
            return $quoteTransfer;
        }

        $quoteReplacementResponseTransfer = $this->servicePointCartFacade->replaceQuoteItems($quoteTransfer);

        return $quoteReplacementResponseTransfer->getQuoteOrFail();
    }
}
