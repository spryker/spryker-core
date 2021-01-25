<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApprovalShipmentConnector\Dependency\Facade;

use Generated\Shared\Transfer\QuoteTransfer;

class QuoteApprovalShipmentConnectorToQuoteApprovalFacadeBridge implements QuoteApprovalShipmentConnectorToQuoteApprovalFacadeInterface
{
    /**
     * @var \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface
     */
    protected $quoteApprovalFacade;

    /**
     * @param \Spryker\Zed\QuoteApproval\Business\QuoteApprovalFacadeInterface $quoteApprovalFacade
     */
    public function __construct($quoteApprovalFacade)
    {
        $this->quoteApprovalFacade = $quoteApprovalFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteInApprovalProcess(QuoteTransfer $quoteTransfer): bool
    {
        return $this->quoteApprovalFacade->isQuoteInApprovalProcess($quoteTransfer);
    }
}
