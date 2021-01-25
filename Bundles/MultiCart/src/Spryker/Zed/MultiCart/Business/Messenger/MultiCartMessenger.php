<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Business\Messenger;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface;
use Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface;

class MultiCartMessenger implements MultiCartMessengerInterface
{
    protected const GLOSSARY_KEY_MULTI_CART_SET_DEFAULT_SUCCESS = 'multi_cart.cart.set_default.success';
    protected const GLOSSARY_KEY_PARAMETER_QUOTE = '%quote%';

    /**
     * @var \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface
     */
    protected $multiCartRepository;

    /**
     * @var \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\MultiCart\Persistence\MultiCartRepositoryInterface $multiCartRepository
     * @param \Spryker\Zed\MultiCart\Dependency\Facade\MultiCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        MultiCartRepositoryInterface $multiCartRepository,
        MultiCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->multiCartRepository = $multiCartRepository;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function addDefaultQuoteChangedMessage(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getCustomer()) {
            return;
        }

        $isQuoteDefault = $this->multiCartRepository->isQuoteDefault(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()->getCustomerReference()
        );

        if ($isQuoteDefault) {
            return;
        }

        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_MULTI_CART_SET_DEFAULT_SUCCESS)
            ->setParameters([static::GLOSSARY_KEY_PARAMETER_QUOTE => $quoteTransfer->getName()]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
