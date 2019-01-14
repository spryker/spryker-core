<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalRequestCanceller implements QuoteApprovalRequestCancellerInterface
{
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        QuoteApprovalToCartFacadeInterface $cartFacade,
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalToMessengerFacadeInterface $messengerFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function cancelQuoteApprovalRequest(
        QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
    ): QuoteResponseTransfer {
        if (!$this->isRequestSentByQuoteOwner($quoteApprovalCancelRequestTransfer)) {
            $this->addPermissionFailedErrorMessage();

            return (new QuoteResponseTransfer())
                ->setIsSuccessful(false)
                ->setQuoteTransfer($quoteApprovalCancelRequestTransfer->getQuote())
                ->setCustomer($quoteApprovalCancelRequestTransfer->getCustomer());
        }

        $quoteTransfer = $quoteApprovalCancelRequestTransfer->getQuote();

        $this->cartFacade->unlockQuote($quoteTransfer);

        $quoteTransfer = $this->removeQuoteApprovalFromQuoteTransferById(
            $quoteTransfer,
            $quoteApprovalCancelRequestTransfer->getIdQuoteApproval()
        );

        $quoteTransfer = $this->removeCartShare($quoteTransfer);

        return $this->quoteFacade->updateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
     *
     * @return bool
     */
    protected function isRequestSentByQuoteOwner(
        QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
    ): bool {
        $requestSender = $quoteApprovalCancelRequestTransfer->getCustomer();
        $quoteOwner = $quoteApprovalCancelRequestTransfer->getQuote()->getCustomer();

        return $requestSender->getCustomerReference() === $quoteOwner->getCustomerReference();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeCartShare(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $quoteTransfer->setShareDetails(new ArrayObject());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idQuoteApproval
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeQuoteApprovalFromQuoteTransferById(
        QuoteTransfer $quoteTransfer,
        int $idQuoteApproval
    ): QuoteTransfer {
        $quoteApprovals = $quoteTransfer->getApprovals();

        foreach ($quoteApprovals as $key => $approval) {
            if ($approval->getIdQuoteApproval() === $idQuoteApproval) {
                $quoteTransfer->getApprovals()->offsetUnset($key);

                break;
            }
        }

        $quoteTransfer->setApprovals($quoteApprovals);

        return $quoteTransfer;
    }

    /**
     * @return void
     */
    protected function addPermissionFailedErrorMessage(): void
    {
        $this->messengerFacade->addErrorMessage(
            $this->createMessageTransfer(
                static::GLOSSARY_KEY_PERMISSION_FAILED
            )
        );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);

        return $messageTransfer;
    }
}
