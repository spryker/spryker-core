<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Model\Hydrator;

use Generated\Shared\Transfer\OfferTransfer;

interface OfferCustomerHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer;
}
