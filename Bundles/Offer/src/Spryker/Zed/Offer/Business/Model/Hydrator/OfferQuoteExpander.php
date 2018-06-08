<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model\Hydrator;

use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Offer\Business\Model\OfferReaderInterface;
use Spryker\Zed\Offer\Dependency\Facade\OfferToCustomerFacadeInterface;

class OfferQuoteExpander implements OfferQuoteExpanderInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Offer\Business\Model\OfferReaderInterface
     */
    protected $offerReader;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\Offer\Business\Model\OfferReaderInterface $offerReader
     */
    public function __construct(OfferToCustomerFacadeInterface $customerFacade, OfferReaderInterface $offerReader)
    {
        $this->offerReader = $offerReader;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idOffer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer, int $idOffer): QuoteTransfer
    {
        $offerTransfer = (new OfferTransfer())
            ->setIdOffer($idOffer);

        $offerTransfer = $this->offerReader
            ->getOfferById($offerTransfer);

        $quoteTransfer = $offerTransfer->getQuote();

        $customerTransfer = $this->customerFacade
            ->findCustomerByReference(
                $offerTransfer->getCustomerReference()
            );

        if ($customerTransfer) {
            $quoteTransfer->setCustomer($customerTransfer);
        }

        return $quoteTransfer;
    }
}
