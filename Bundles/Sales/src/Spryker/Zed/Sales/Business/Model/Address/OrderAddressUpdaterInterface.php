<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Model\Address;

use Generated\Shared\Transfer\AddressTransfer;

/**
 * @deprecated Use \Spryker\Zed\Sales\Business\Address\OrderAddressWriterInterface interface instead.
 */
interface OrderAddressUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return bool
     */
    public function update(AddressTransfer $addressTransfer, $idAddress);
}
