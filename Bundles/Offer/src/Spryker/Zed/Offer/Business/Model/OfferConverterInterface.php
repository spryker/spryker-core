<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface OfferConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $offer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertToOrder(OrderTransfer $offer): OfferToOrderConvertResponseTransfer;
}
