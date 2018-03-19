<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer;

use Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer;
use Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer;

/**
 * @method \Spryker\Client\Offer\OfferFactory getFactory()
 */
interface OfferClientInterface
{
    /**
     * Specification:
     * - Requests order to offer conversion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $offerToOrderConvertRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer $offerToOrderRequestTransfer
     */
    public function convertOfferToOrder(OfferToOrderConvertRequestTransfer $offerToOrderConvertRequestTransfer): OfferToOrderConvertResponseTransfer;
}
