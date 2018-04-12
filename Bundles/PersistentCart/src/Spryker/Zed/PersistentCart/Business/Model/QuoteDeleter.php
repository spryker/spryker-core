<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Model;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\PermissionAwareTrait;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteDeleter implements QuoteDeleterInterface
{
    use PermissionAwareTrait;

    public const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    public const GLOSSARY_KEY_REMOVE_SUCCESS = 'persistent_cart.quote.remove.success';

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface
     */
    protected $quoteResponseExpander;

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteResponseExpanderInterface $quoteResponseExpander
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteResponseExpanderInterface $quoteResponseExpander,
        PersistentCartToMessengerFacadeInterface $messengerFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteResponseExpander = $quoteResponseExpander;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        if (!$this->isQuoteWriteAllowed($quoteTransfer, $quoteTransfer->getCustomer())) {
            $quoteResponseTransfer = new QuoteResponseTransfer();
            $quoteResponseTransfer->setIsSuccessful(false);
            $quoteResponseTransfer->setQuoteTransfer($quoteTransfer);
            $quoteResponseTransfer->setCustomer($quoteTransfer->getCustomer());
            return $this->quoteResponseExpander->expand($quoteResponseTransfer);
        }
        $quoteResponseTransfer = $this->quoteResponseExpander->expand($this->quoteFacade->deleteQuote($quoteTransfer));
        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage();
        }

        return $quoteResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isQuoteWriteAllowed(QuoteTransfer $quoteTransfer, CustomerTransfer $customerTransfer): bool
    {
        if (strcmp($customerTransfer->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0
            || ($customerTransfer->getCompanyUserTransfer()
                && $this->can('WriteSharedCartPermissionPlugin', $customerTransfer->getCompanyUserTransfer()->getIdCompanyUser(), $quoteTransfer->getIdQuote())
            )
        ) {
            return true;
        }
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_PERMISSION_FAILED);
        $this->messengerFacade->addErrorMessage($messageTransfer);

        return false;
    }

    /**
     * @return void
     */
    protected function addSuccessMessage(): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::GLOSSARY_KEY_REMOVE_SUCCESS);
        $this->messengerFacade->addSuccessMessage($messageTransfer);
    }
}
