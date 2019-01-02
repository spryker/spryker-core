<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;


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
        return QuoteApprovalConfig::STATUS_APPROVED;//Has to be implemented in story PS-4362.
    }
}
