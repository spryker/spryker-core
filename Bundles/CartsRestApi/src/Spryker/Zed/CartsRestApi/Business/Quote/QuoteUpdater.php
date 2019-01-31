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
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
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
    protected $cartReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $cartReader
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        CartsRestApiToCartFacadeInterface $cartFacade,
        QuoteReaderInterface $cartReader,
        QuoteMapperInterface $quoteMapper
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartFacade = $cartFacade;
        $this->cartReader = $cartReader;
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
            ->requireCustomerReference()
            ->requireQuoteUuid();

        $quoteTransfer = $restQuoteRequestTransfer->getQuote();
        $quoteResponseTransfer = $this->cartReader->findQuoteByUuid($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $originalQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteTransfer = $this->processQuoteData($quoteTransfer, $originalQuoteTransfer);
        $quoteResponseTransfer = $this->validateQuoteResponse(
            $originalQuoteTransfer,
            $quoteTransfer,
            $quoteResponseTransfer
        );

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $quoteTransfer = $this->cartFacade->reloadItems(
                $this->quoteMapper->mapOriginalQuoteTransferToQuoteTransfer($originalQuoteTransfer)
            );
            $quoteUpdateRequestTransfer = $this->quoteMapper->mapQuoteTransferToQuoteUpdateRequestTransfer($quoteTransfer);
            $quoteUpdateRequestAttributesTransfer = $this->quoteMapper->mapQuoteTransferToQuoteUpdateRequestAttributesTransfer($quoteTransfer);
            $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
            $quoteResponseTransfer = $this->persistentCartFacade->updateQuote($quoteUpdateRequestTransfer);
        }

        return $quoteResponseTransfer;
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

        $quoteCollectionResponseTransfer = $this->cartReader->findQuoteByCustomerAndStore(
            (new RestQuoteCollectionRequestTransfer())
                ->setCustomerReference($assigningGuestQuoteRequestTransfer->getAnonymousCustomerReference())
        );

        $registeredCustomer = $assigningGuestQuoteRequestTransfer->getCustomer();
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference(
                $registeredCustomer->getCustomerReference()
            )->setUuid($quoteCollectionResponseTransfer->getQuoteCollection()->getQuotes()[0]->getUuid())
            ->setCustomer($registeredCustomer);

        return $this->updateQuote(
            $this->quoteMapper->mapQuoteTransferToRestQuoteRequestTransfer($quoteTransfer, $registeredCustomer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $originalQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function processQuoteData(QuoteTransfer $quoteTransfer, QuoteTransfer $originalQuoteTransfer): QuoteTransfer
    {
        if ($quoteTransfer->getCurrency() === null || !$quoteTransfer->getCurrency()->getCode()) {
            $quoteTransfer->setCurrency($originalQuoteTransfer->getCurrency());
        }

        if ($quoteTransfer->getStore() === null || !$quoteTransfer->getStore()->getName()) {
            $quoteTransfer->setStore($originalQuoteTransfer->getStore());
        }

        return $quoteTransfer;
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
        if (count($originalQuoteTransfer->getItems()) > 0 && $quoteTransfer->getPriceMode()) {
            $quoteResponseTransfer
                ->setIsSuccessful(false)
                ->addError((new QuoteErrorTransfer())
                    ->setMessage(CartsRestApiConfig::RESPONSE_MESSAGE_PRICE_MODE_CANT_BE_CHANGED));
        }

        return $quoteResponseTransfer;
    }
}
