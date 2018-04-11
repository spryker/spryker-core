<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Plugin\ManualOrderEntryGui;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin\QuoteExpanderPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class OfferQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface
{
    public const PARAM_ID_OFFER = 'id-offer';

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer, Request $request): QuoteTransfer
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        if (!$idOffer) {
            return $quoteTransfer;
        }

        $quoteTransfer = $this->getFactory()->getOfferFacade()->expandQuoteUsingOffer($quoteTransfer, $idOffer);

        return $quoteTransfer;
    }
}
