<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer\Zed;

use Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;

interface OfferStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $offerToOrderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrder(OfferToOrderConvertRequestTransfer $offerToOrderRequestTransfer): OfferToOrderConvertResponseTransfer;
}
