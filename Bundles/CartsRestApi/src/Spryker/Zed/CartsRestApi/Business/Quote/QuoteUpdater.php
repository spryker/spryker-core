<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker;
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
     * @var \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker
     */
    protected $quotePermissionChecker;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     * @param \Spryker\Zed\CartsRestApi\Business\PermissionChecker\QuotePermissionChecker $quotePermissionChecker
     */
    public function __construct(
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        CartsRestApiToCartFacadeInterface $cartFacade,
        QuoteReaderInterface $quoteReader,
        QuoteMapperInterface $quoteMapper,
        QuotePermissionChecker $quotePermissionChecker
    ) {
        $this->persistentCartFacade = $persistentCartFacade;
        $this->cartFacade = $cartFacade;
        $this->quoteReader = $quoteReader;
        $this->quoteMapper = $quoteMapper;
        $this->quotePermissionChecker = $quotePermissionChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();

        if (!$quoteTransfer->getUuid()) {
            $quoteResponseTransfer = (new QuoteResponseTransfer())
                ->addError((new QuoteErrorTransfer())->setMessage(CartsRestApiSharedConfig::RESPONSE_CODE_CART_ID_MISSING));

            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteResponseTransfer = $this->quoteReader->findQuoteByUuid($quoteTransfer);
        if ($quoteResponseTransfer->getIsSuccessful() === false) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        $quoteTransfer->setIdQuote($quoteResponseTransfer->getQuoteTransfer()->getIdQuote());

        if (!$this->quotePermissionChecker->checkQuoteWritePermission($quoteTransfer)) {
            return $quoteResponseTransfer
                ->addErrorCode(CartsRestApiSharedConfig::RESPONSE_CODE_UNAUTHORIZED_ACTION);
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
     * @param \Generated\Shared\Transfer\AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function assignGuestCartToRegisteredCustomer(AssignGuestQuoteRequestTransfer $assignGuestQuoteRequestTransfer): QuoteResponseTransfer
    {
        $assignGuestQuoteRequestTransfer
            ->requireCustomerReference()
            ->requireAnonymousCustomerReference();

        $quoteCollectionResponseTransfer = $this->quoteReader->getQuoteCollectionByCustomerAndStore(
            (new CustomerTransfer())
                ->setCustomerReference($assignGuestQuoteRequestTransfer->getAnonymousCustomerReference())
        );

        $registeredCustomerReference = $assignGuestQuoteRequestTransfer->getCustomerReference();
        $quoteTransfer = $this->quoteMapper->createQuoteTransfer($registeredCustomerReference, $quoteCollectionResponseTransfer);

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
        if ($originalQuoteTransfer->getItems()->count() > 0
            && $quoteTransfer->getPriceMode() !== $originalQuoteTransfer->getPriceMode()
        ) {
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
