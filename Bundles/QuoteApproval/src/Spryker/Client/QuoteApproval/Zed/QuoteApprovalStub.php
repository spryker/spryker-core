<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface;

class QuoteApprovalStub implements QuoteApprovalStubInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Zed\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(QuoteApprovalToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function approveQuote(int $idQuote): QuoteTransfer
    {
        $quoteTransfer = (new QuoteTransfer)->setIdQuote($idQuote); // todo: move to a separate function

        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        $quoteTransfer = $this->zedRequestClient->call(
            '/quote-approval/gateway/approve-quote',
            $quoteTransfer
        );

        return $quoteTransfer;
    }
}
