<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\QuoteApproveRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalFactory getFactory()
 */
class QuoteApprovalClient extends AbstractClient implements QuoteApprovalClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function getQuoteStatus(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->getFactory()
            ->createQuoteApprovalStatusCalculator()
            ->calculateQuoteStatus($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteApproveRequestTransfer $quoteApproveRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function sendApproveRequest(
        QuoteApproveRequestTransfer $quoteApproveRequestTransfer
    ): QuoteResponseTransfer {
        $quoteResponseTransfer = $this->getFactory()->createZedStub()->sendApproveRequest($quoteApproveRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->getFactory()->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());
        }

        return $quoteResponseTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getPotentialQuoteApproversList(QuoteTransfer $quoteTransfer): CompanyUserCollectionTransfer
    {
        return $this->getFactory()->createZedStub()->getPotentialQuoteApproversList($quoteTransfer);
    }
}
