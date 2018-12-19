<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Zed;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalStubInterface
{
    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function approveQuote(int $idQuote): QuoteTransfer;
}
