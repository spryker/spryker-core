<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Mapper;

use Generated\Shared\Transfer\AddressTransfer;

interface SalesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $modifiedAddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapAddressTransferToAddressTransfer(
        AddressTransfer $addressTransfer,
        AddressTransfer $modifiedAddressTransfer
    ): AddressTransfer;
}
