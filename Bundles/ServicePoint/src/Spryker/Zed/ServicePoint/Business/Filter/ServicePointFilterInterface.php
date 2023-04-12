<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointCollectionResponseTransfer;

interface ServicePointFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $validServicePointTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $invalidServicePointTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer>
     */
    public function mergeServicePoints(
        ArrayObject $validServicePointTransfers,
        ArrayObject $invalidServicePointTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionResponseTransfer $servicePointCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer>>
     */
    public function filterServicePointsByValidity(
        ServicePointCollectionResponseTransfer $servicePointCollectionResponseTransfer
    ): array;
}
