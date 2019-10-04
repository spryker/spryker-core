<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    public function calculateQuoteStatus(QuoteTransfer $quoteTransfer): ?string;
}
