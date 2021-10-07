<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer\Model\Hydrator;

use ArrayObject;

interface OfferHydratorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OfferTransfer> $offerTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\OfferTransfer>
     */
    public function hydrateQuoteWithCustomer(ArrayObject $offerTransfers): ArrayObject;
}
