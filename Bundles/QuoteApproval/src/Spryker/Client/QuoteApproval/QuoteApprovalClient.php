<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuoteApproval\QuoteApprovalFactory getFactory()
 */
class QuoteApprovalClient extends AbstractClient implements QuoteApprovalClientInterface
{
    /**
     * @api
     *
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function approveQuote(int $idQuote): QuoteTransfer
    {
        return $this->getFactory()
            ->createQuoteApprovalStub()
            ->approveQuote($idQuote);
    }
}
