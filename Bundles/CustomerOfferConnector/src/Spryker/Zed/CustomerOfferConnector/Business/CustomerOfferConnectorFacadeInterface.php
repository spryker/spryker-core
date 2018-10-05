<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerOfferConnector\Business;

use Generated\Shared\Transfer\OfferTransfer;

interface CustomerOfferConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Puts customer transfer into offer transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrateOfferWithCustomer(OfferTransfer $offerTransfer): OfferTransfer;
}
