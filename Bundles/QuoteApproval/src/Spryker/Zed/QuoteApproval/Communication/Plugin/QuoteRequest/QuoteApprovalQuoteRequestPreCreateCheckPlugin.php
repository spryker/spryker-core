<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestPreCreateCheckPluginInterface;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalQuoteRequestPreCreateCheckPlugin extends AbstractPlugin implements QuoteRequestPreCreateCheckPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns false if quote doesn't have status `waiting`, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return !$this->getFacade()->isQuoteWaitingForApproval(
            $quoteRequestTransfer
                ->requireLatestVersion()
                ->getLatestVersion()
                ->requireQuote()
                ->getQuote()
        );
    }
}
