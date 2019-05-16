<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;

class QuoteCreator implements QuoteCreatorInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected $quoteCreatorPlugin;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface $quoteCreatorPlugin
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        QuoteCreatorPluginInterface $quoteCreatorPlugin,
        QuoteReaderInterface $quoteReader,
        QuoteMapperInterface $quoteMapper,
        CartsRestApiToStoreFacadeInterface $storeFacade
    ) {
        $this->quoteCreatorPlugin = $quoteCreatorPlugin;
        $this->quoteReader = $quoteReader;
        $this->quoteMapper = $quoteMapper;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireCustomer();

        $store = $quoteTransfer->getStore();
        if ($store && $store->getName() !== $this->storeFacade->getCurrentStore()->getName()) {
            return (new QuoteResponseTransfer())
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_STORE_DATA_IS_INVALID));
        }

        $quoteResponseTransfer = $this->quoteCreatorPlugin->createQuote($quoteTransfer);
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
