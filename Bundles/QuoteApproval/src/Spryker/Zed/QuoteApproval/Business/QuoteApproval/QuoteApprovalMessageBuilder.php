<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business\QuoteApproval;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteApprovalTransfer;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;

class QuoteApprovalMessageBuilder implements QuoteApprovalMessageBuilderInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        QuoteApprovalToQuoteFacadeInterface $quoteFacade,
        QuoteApprovalToCustomerFacadeInterface $customerFacade
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteApprovalTransfer $quoteApprovalTransfer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function getSuccessMessage(QuoteApprovalTransfer $quoteApprovalTransfer, string $status): MessageTransfer
    {
        $quoteTransfer = $this->quoteFacade->findQuoteById($quoteApprovalTransfer->getFkQuote());
        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($quoteTransfer->getQuoteTransfer()->getCustomerReference());
        $messageTransfer = (new MessageTransfer())->setValue('quote_approval_widget.cart.success_message.' . $status)
            ->setParameters([
                'first_name' => $customerResponseTransfer->getCustomerTransfer()->getFirstName(),
                'last_name' => $customerResponseTransfer->getCustomerTransfer()->getLastName(),
            ]);

        return $messageTransfer;
    }
}
