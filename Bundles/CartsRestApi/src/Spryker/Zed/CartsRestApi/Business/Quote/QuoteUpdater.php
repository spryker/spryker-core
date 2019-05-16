<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();
        $quoteTransfer->requireUuid();

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid($quoteTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }

        $originalQuoteTransfer = $quoteResponseTransfer->getQuoteTransfer();
        $quoteResponseTransfer = $this->validateQuoteResponse($originalQuoteTransfer, $quoteTransfer, $quoteResponseTransfer);

        if (count($quoteResponseTransfer->getErrors()) > 0) {
            return $quoteResponseTransfer;
        }

        $quoteTransfer = $this->cartFacade->reloadItems(
            $this->quoteMapper->mapQuoteTransferToOriginalQuoteTransfer($quoteTransfer, $originalQuoteTransfer)
        );

        return $this->performUpdatingQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomer(AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer): QuoteResponseTransfer
    {
        $assignGuestQuoteRequestTransfer
            ->requireCustomerReference()
            ->requireAnonymousCustomerReference();

        $quoteCollectionTransfer = $this->quoteReader->getQuoteByQuoteCriteriaFilter(
            (new QuoteCriteriaFilterTransfer())
                ->setCustomerReference($assignGuestQuoteRequestTransfer->getAnonymousCustomerReference())
        );

        if (!$quoteCollectionTransfer->getQuotes()->count()) {
            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false);
        }

        $registeredCustomerReference = $assignGuestQuoteRequestTransfer->getCustomerReference();
        $quoteTransfer = $this->createQuoteTransfer($registeredCustomerReference, $quoteCollectionTransfer);

        return $this->performUpdatingQuote($quoteTransfer);
    }

    /**
     * @param string $registeredCustomerReference
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(
        string $registeredCustomerReference,
        QuoteCollectionTransfer $quoteCollectionTransfer
    ): QuoteTransfer {
        $registeredCustomer = (new CustomerTransfer())->setCustomerReference($registeredCustomerReference);
        $quoteTransfer = $quoteCollectionTransfer->getQuotes()[0];
        $quoteTransfer->setCustomerReference($registeredCustomerReference);

        return $quoteTransfer->setCustomer($registeredCustomer);
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
        if ($originalQuoteTransfer->getItems()->count() > 0
            && ($quoteTransfer->getPriceMode() && $quoteTransfer->getPriceMode() !== $originalQuoteTransfer->getPriceMode())
        ) {
            return $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CART_CANT_BE_UPDATED));
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
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $this->setQuoteErrorTransfersToQuoteResponse($quoteResponseTransfer);
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function setQuoteErrorTransfersToQuoteResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ErrorMessageTransfer[] $errorMessageTransfers */
        $errorMessageTransfers = $quoteResponseTransfer->getErrors();

        $quoteErrorTransfers = new ArrayObject();
        foreach ($errorMessageTransfers as $errorMessageTransfer) {
            $quoteErrorTransfers[] = $this->quoteMapper->mapErrorMessageTransferToQuoteErrorTransfer(
                $errorMessageTransfer,
                new QuoteErrorTransfer()
            );
        }

        return $quoteResponseTransfer->setErrors($quoteErrorTransfers);
    }
}
