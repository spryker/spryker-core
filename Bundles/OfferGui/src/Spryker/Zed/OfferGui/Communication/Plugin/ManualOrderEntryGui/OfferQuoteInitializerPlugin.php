<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Plugin\ManualOrderEntryGui;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Dependency\Plugin\QuoteInitializerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class OfferQuoteInitializerPlugin implements QuoteInitializerPluginInterface
{
    public const PARAM_ID_OFFER = 'id-offer';

    public function initializeQuote(Request $request): ?QuoteTransfer
    {
        $idOffer = $request->get(static::PARAM_ID_OFFER);

        if (!$idOffer) {
            return null;
        }

        $offerTransfer = (new OfferTransfer())
            ->setIdOffer($idOffer);

        $offerTransfer = $this->getFactory()
            ->getOfferFacade()
            ->getOfferById($offerTransfer);

        $quoteTransfer = $offerTransfer->getQuote();

        $customerTransfer = $this->getFactory()
            ->getCustomerFacade()
            ->findCustomerByReference(
                $offerTransfer->getCustomerReference()
            );

        if ($customerTransfer) {
            $quoteTransfer->setCustomer($customerTransfer);
            $quoteTransfer->setIdCustomer($customerTransfer->getIdCustomer());
        }

        return $quoteTransfer;
    }
}
