<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalQuoteFieldsAllowedForSavingProviderPlugin extends AbstractPlugin implements QuoteFieldsAllowedForSavingProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns required quote fields from config if approval request is not canceled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function execute(QuoteTransfer $quoteTransfer): array
    {
        return $this->getFacade()->getQuoteFieldsAllowedForSavingByQuoteApprovalStatus($quoteTransfer);
    }
}
