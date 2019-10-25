<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Checker;

use Countable;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\QuoteApproval\QuoteApprovalConfig;

class QuoteChecker implements QuoteCheckerInterface
{
    /**
     * @var \Spryker\Client\QuoteApproval\QuoteApprovalConfig
     */
    protected $quoteApprovalConfig;

    /**
     * @var \Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApplicableForApprovalCheckPluginInterface[]
     */
    protected $quoteApplicableForApprovalCheckPlugins;

    /**
     * @param \Spryker\Client\QuoteApproval\QuoteApprovalConfig $quoteApprovalConfig
     * @param \Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApplicableForApprovalCheckPluginInterface[] $quoteApplicableForApprovalCheckPlugins
     */
    public function __construct(QuoteApprovalConfig $quoteApprovalConfig, array $quoteApplicableForApprovalCheckPlugins)
    {
        $this->quoteApprovalConfig = $quoteApprovalConfig;
        $this->quoteApplicableForApprovalCheckPlugins = $quoteApplicableForApprovalCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForApprovalProcess(QuoteTransfer $quoteTransfer): bool
    {
        $quoteData = $quoteTransfer->toArray(false, true);

        foreach ($this->quoteApprovalConfig->getRequiredQuoteFieldsForApprovalProcess() as $requiredQuoteField) {
            if (empty($quoteData[$requiredQuoteField])) {
                return false;
            }

            if ($quoteData[$requiredQuoteField] instanceof Countable && !count($quoteData[$requiredQuoteField])) {
                return false;
            }
        }

        return $this->executeQuoteApplicableForApprovalCheckPlugins($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeQuoteApplicableForApprovalCheckPlugins(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->quoteApplicableForApprovalCheckPlugins as $quoteApplicableForApprovalCheckPlugin) {
            if (!$quoteApplicableForApprovalCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
