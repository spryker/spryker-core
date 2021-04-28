<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Communication\Plugin\Quote;

use ArrayObject;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuotePostExpanderPluginInterface;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuotePreExpanderPluginInterface;

/**
 * @method \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface getFacade()
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface, QuotePreExpanderPluginInterface, QuotePostExpanderPluginInterface
{
    /**
     * @var int[]
     */
    protected $quoteIds = [];

    /**
     * @var \Generated\Shared\Transfer\QuoteApprovalTransfer[]|null
     */
    protected $quoteApprovalsByIdQuote;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function preExpand(QuoteTransfer $quoteTransfer): void
    {
        $this->quoteIds[] = $quoteTransfer->getIdQuoteOrFail();
    }

    /**
     * {@inheritDoc}
     * - Expands quote with approvals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->preloadApprovals();

        $quoteApprovals = $this->getQuoteApprovalsByIdQuote($quoteTransfer->getIdQuote());

        $quoteTransfer->setQuoteApprovals($quoteApprovals);

        return $quoteTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function postExpand(): void
    {
        $this->quoteIds = [];
        $this->quoteApprovalsByIdQuote = null;
    }

    /**
     * @return void
     */
    protected function preloadApprovals(): void
    {
        if ($this->quoteApprovalsByIdQuote !== []) {
            return;
        }

        $quoteApprovalsRequestTransfer = new QuoteApprovalRequestTransfer();
        $quoteApprovalsRequestTransfer->setQuoteIds($this->quoteIds);

        $this->quoteApprovalsByIdQuote = $this->getFacade()->getQuoteApprovals($quoteApprovalsRequestTransfer);
    }

    /**
     * @param int $idQuote
     *
     * @return \ArrayObject
     */
    protected function getQuoteApprovalsByIdQuote(int $idQuote): ArrayObject
    {
        if (!isset($this->quoteApprovalsByIdQuote[$idQuote])) {
            $this->quoteApprovalsByIdQuote[$idQuote] = new ArrayObject($this->getFacade()->getQuoteApprovalsByIdQuote($idQuote));
        }

        return $this->quoteApprovalsByIdQuote[$idQuote];
    }
}
