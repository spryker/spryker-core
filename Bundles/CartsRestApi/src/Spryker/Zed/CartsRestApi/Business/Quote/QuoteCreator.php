<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\CartsRestApi\CartsRestApiConfig as CartsRestApiSharedConfig;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;

class QuoteCreator implements QuoteCreatorInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected $quoteCreatorPlugin;

    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdderInterface
     */
    protected $quoteErrorIdentifierAdder;

    /**
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface $quoteCreatorPlugin
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteErrorIdentifierAdderInterface $quoteErrorIdentifierAdder
     */
    public function __construct(
        QuoteCreatorPluginInterface $quoteCreatorPlugin,
        CartsRestApiToStoreFacadeInterface $storeFacade,
        QuoteErrorIdentifierAdderInterface $quoteErrorIdentifierAdder
    ) {
        $this->quoteCreatorPlugin = $quoteCreatorPlugin;
        $this->storeFacade = $storeFacade;
        $this->quoteErrorIdentifierAdder = $quoteErrorIdentifierAdder;
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
            $quoteResponseTransfer = $this->quoteErrorIdentifierAdder->addErrorIdentifiersToQuoteResponseErrors($quoteResponseTransfer);
            $quoteResponseTransfer
                ->addError((new QuoteErrorTransfer())
                    ->setErrorIdentifier(CartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CREATING_CART));
        }

        return $quoteResponseTransfer;
    }
}
