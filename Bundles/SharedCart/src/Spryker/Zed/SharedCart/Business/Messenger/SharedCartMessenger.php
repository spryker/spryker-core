<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Messenger;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToMessengerFacadeInterface;
use Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface;

class SharedCartMessenger implements SharedCartMessengerInterface
{
    protected const GLOSSARY_KEY_SHARED_CART_SET_DEFAULT_SUCCESS = 'shared_cart.cart.set_default.success';
    protected const GLOSSARY_KEY_PARAMETER_QUOTE = '%quote%';

    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface
     */
    protected $sharedCartRepository;

    /**
     * @var \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartRepositoryInterface $sharedCartRepository
     * @param \Spryker\Zed\SharedCart\Dependency\Facade\SharedCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        SharedCartRepositoryInterface $sharedCartRepository,
        SharedCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->sharedCartRepository = $sharedCartRepository;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function addDefaultSharedQuoteChangedMessage(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getCustomer()) {
            return;
        }

        if ($quoteTransfer->getCustomer()->getCustomerReference() === $quoteTransfer->getCustomerReference()) {
            return;
        }

        $isSharedQuoteDefault = $this->sharedCartRepository->isSharedQuoteDefault(
            $quoteTransfer->getIdQuote(),
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser()
        );

        if ($isSharedQuoteDefault) {
            return;
        }

        $messageTransfer = (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_SHARED_CART_SET_DEFAULT_SUCCESS)
            ->setParameters([static::GLOSSARY_KEY_PARAMETER_QUOTE => $quoteTransfer->getName()]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
