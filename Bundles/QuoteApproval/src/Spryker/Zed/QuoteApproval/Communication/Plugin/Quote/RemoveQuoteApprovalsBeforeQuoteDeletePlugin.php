<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class RemoveQuoteApprovalsBeforeQuoteDeletePlugin extends AbstractPlugin implements QuoteWritePluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes quote approvals from Persistence before quote deletion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->requireIdQuote();

        $this->getFacade()->removeApprovalsByIdQuote($quoteTransfer->getIdQuote());

        return $quoteTransfer;
    }
}
