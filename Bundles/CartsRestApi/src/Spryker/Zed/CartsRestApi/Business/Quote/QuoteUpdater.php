<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class QuoteUpdater implements QuoteUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        CartsRestApiToCartFacadeInterface $cartFacade,
        QuoteReaderInterface $quoteReader,
        QuoteMapperInterface $quoteMapper
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartFacade = $cartFacade;
        $this->quoteReader = $quoteReader;
        $this->quoteMapper = $quoteMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        $restQuoteRequestTransfer
            ->requireQuote()
            ->requireCustomerReference();

        $restQuoteRequestTransfer->getQuote()->requireCustomer();

        if (!$restQuoteRequestTransfer->getQuoteUuid()) {
            $quoteResponseTransfer = $this->quoteMapper->createQuoteResponseTransfer($restQuoteRequestTransfer)
                ->addError((new QuoteErrorTransfer())->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_CART_ID_MISSING));

            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteTransfer = $restQuoteRequestTransfer->getQuote();

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid($restQuoteRequestTransfer->getQuote());
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $originalQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        $this->validateQuoteResponse($originalQuoteTransfer, $quoteTransfer, $quoteResponseTransfer);

        if (count($quoteResponseTransfer->getErrors()) > 0) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->cartFacade->reloadItems(
            $this->quoteMapper->mapQuoteTransferToOriginalQuoteTransfer($quoteTransfer, $originalQuoteTransfer)
        );

        return $this->performUpdatingQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomer(AssigningGuestQuoteRequestTransfer $assigningGuestQuoteRequestTransfer): QuoteResponseTransfer
    {
        $assigningGuestQuoteRequestTransfer
            ->requireCustomer()
            ->requireAnonymousCustomerReference();

        $quoteCollectionResponseTransfer = $this->quoteReader->getQuoteCollectionByCustomerAndStore(
            (new RestQuoteCollectionRequestTransfer())
                ->setCustomerReference($assigningGuestQuoteRequestTransfer->getAnonymousCustomerReference())
        );

        $registeredCustomer = $assigningGuestQuoteRequestTransfer->getCustomer();
        $quoteTransfer = $this->quoteMapper->createQuoteTransfer($registeredCustomer, $quoteCollectionResponseTransfer);

        return $this->performUpdatingQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function validateQuoteResponse(
        QuoteTransfer $originalQuoteTransfer,
        QuoteTransfer $quoteTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer {
        if ($originalQuoteTransfer->getItems()->count() > 0 && $quoteTransfer->getPriceMode()) {
            return $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_CART_CANT_BE_UPDATED));
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function performUpdatingQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteUpdateRequestTransfer = $this->quoteMapper->mapQuoteTransferToQuoteUpdateRequestTransfer($quoteTransfer);

        $quoteResponseTransfer = $this->persistentCartFacade->updateQuote($quoteUpdateRequestTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }
}
