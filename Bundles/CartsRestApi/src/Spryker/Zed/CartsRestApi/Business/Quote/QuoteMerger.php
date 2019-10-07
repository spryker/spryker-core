<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;

class QuoteMerger implements QuoteMergerInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        CartsRestApiToQuoteFacadeInterface $quoteFacade
    ) {
        $this->quoteReader = $quoteReader;
        $this->persistentCartFacade = $persistentCartFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return void
     */
    public function mergeGuestQuoteAndCustomerQuote(OauthResponseTransfer $oauthResponseTransfer): void
    {
        $customerReference = $oauthResponseTransfer->getCustomerReference();
        $anonymousCustomerReference = $oauthResponseTransfer->getAnonymousCustomerReference();
        if (!$anonymousCustomerReference || !$customerReference) {
            return;
        }

        $guestQuoteTransfer = $this->findQuoteByCustomerReference($anonymousCustomerReference);
        if (!$guestQuoteTransfer) {
            return;
        }

        if (!$guestQuoteTransfer->getItems()->count()) {
            return;
        }

        $customerQuoteTransfer = $this->findQuoteByCustomerReference($customerReference);
        if (!$customerQuoteTransfer) {
            return;
        }

        $this->addGuestQuoteItemsToCustomerCart($guestQuoteTransfer, $customerQuoteTransfer);

        $guestQuoteTransfer->setCustomer((new CustomerTransfer())->setCustomerReference($anonymousCustomerReference));
        $this->quoteFacade->deleteQuote($guestQuoteTransfer);
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function findQuoteByCustomerReference(string $customerReference): ?QuoteTransfer
    {
        $quoteCollectionTransfer = $this->quoteReader->getQuoteCollection(
            (new QuoteCriteriaFilterTransfer())->setCustomerReference($customerReference)
        );

        $quoteTransfers = $quoteCollectionTransfer->getQuotes();
        if (!$quoteTransfers->count()) {
            return null;
        }

        return $quoteTransfers[0];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $guestQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $customerQuoteTransfer
     *
     * @return void
     */
    protected function addGuestQuoteItemsToCustomerCart(
        QuoteTransfer $guestQuoteTransfer,
        QuoteTransfer $customerQuoteTransfer
    ): void {
        $persistentCartChangeTransfer = (new PersistentCartChangeTransfer())
            ->setCustomer((new CustomerTransfer())->setCustomerReference($customerQuoteTransfer->getCustomerReference()))
            ->setIdQuote($customerQuoteTransfer->getIdQuote())
            ->setItems($guestQuoteTransfer->getItems());

        $this->persistentCartFacade->add($persistentCartChangeTransfer);
    }
}
