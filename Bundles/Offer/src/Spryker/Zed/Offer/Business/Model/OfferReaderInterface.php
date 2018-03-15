<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OrderListTransfer;

interface OfferReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $offerListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOfferList(OrderListTransfer $offerListTransfer): OrderListTransfer;
}
