<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;

interface ServicePointAddressFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $validServicePointAddressTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $invalidServicePointAddressTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer>
     */
    public function mergeServicePointAddresses(
        ArrayObject $validServicePointAddressTransfers,
        ArrayObject $invalidServicePointAddressTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer>>
     */
    public function filterServicePointAddressesByValidity(
        ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
    ): array;
}
