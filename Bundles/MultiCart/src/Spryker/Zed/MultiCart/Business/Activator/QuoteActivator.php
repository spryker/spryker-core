<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Activator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteActivationRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface;

class QuoteActivator implements QuoteActivatorInterface
{
    public const MULTI_CART_SET_DEFAULT_SUCCESS = 'multi_cart.cart.set_default.success';

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        MultiCartToQuoteFacadeInterface $quoteFacade,
        MultiCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setDefaultQuote(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer
    {
        return $this->executeSetDefaultQuoteTransaction($quoteActivationRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteActivationRequestTransfer $quoteActivationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function executeSetDefaultQuoteTransaction(QuoteActivationRequestTransfer $quoteActivationRequestTransfer): QuoteResponseTransfer
    {
        $quoteResponseTransfer = $this->quoteFacade->findQuoteById($quoteActivationRequestTransfer->getIdQuote());

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $quoteResponseTransfer;
        }
        $quoteTransfer = $quoteResponseTransfer->getQuoteTransfer();

        $quoteTransfer
            ->setCustomer($quoteActivationRequestTransfer->getCustomer())
            ->setIsDefault(true);

        $this->addSuccessMessage($quoteTransfer->getName());

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param string $quoteName
     *
     * @return void
     */
    protected function addSuccessMessage($quoteName): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer
            ->setValue(static::MULTI_CART_SET_DEFAULT_SUCCESS)
            ->setParameters(['%quote%' => $quoteName]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
