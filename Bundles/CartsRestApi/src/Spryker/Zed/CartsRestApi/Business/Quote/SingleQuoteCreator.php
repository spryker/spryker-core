<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\QuoteCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface;

class SingleQuoteCreator implements SingleQuoteCreatorInterface
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
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     */
    public function __construct(
        QuoteReaderInterface $quoteReader,
        CartsRestApiToPersistentCartFacadeInterface $persistentCartFacade,
        QuoteMapperInterface $quoteMapper
    ) {
        $this->quoteReader = $quoteReader;
        $this->persistentCartFacade = $persistentCartFacade;
        $this->quoteMapper = $quoteMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createSingleQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteCriteriaFilterTransfer = (new QuoteCriteriaFilterTransfer())
            ->setCustomerReference($quoteTransfer->getCustomerReference())
            ->setIdStore($quoteTransfer->getStore()->getIdStore());

        $quoteCollectionTransfer = $this->quoteReader->getQuoteCollectionByQuoteCriteriaFilter($quoteCriteriaFilterTransfer);
        if ($quoteCollectionTransfer->getQuotes()->count()) {
            $quoteErrorTransfer = (new QuoteErrorTransfer())
                ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_CUSTOMER_ALREADY_HAS_CART);

            return (new QuoteResponseTransfer())
                ->addError($quoteErrorTransfer)
                ->setIsSuccessful(false);
        }

        $quoteResponseTransfer = $this->persistentCartFacade->createQuote($quoteCollectionTransfer->getQuotes()[0]);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $this->setQuoteErrorTransfersToQuoteResponse($quoteResponseTransfer);
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATING_CART)
                );
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
        /** @var  \Generated\Shared\Transfer\ErrorMessageTransfer[] $errorMessageTransfers */
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
