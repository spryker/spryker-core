<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalRequestCanceller implements QuoteApprovalRequestCancellerInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface $cartFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     */
    public function __construct(
        QuoteApprovalToCartFacadeInterface $cartFacade,
        QuoteApprovalToQuoteFacadeInterface $quoteFacade
    ) {
        $this->cartFacade = $cartFacade;
        $this->quoteFacade = $quoteFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function cancelQuoteApprovalRequest(
        QuoteApprovalCancelRequestTransfer $quoteApprovalCancelRequestTransfer
    ): QuoteResponseTransfer {
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
}
