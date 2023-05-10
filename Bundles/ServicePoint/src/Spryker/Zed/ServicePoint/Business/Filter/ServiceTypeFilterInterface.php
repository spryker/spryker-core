<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer;

interface ServiceTypeFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $validServiceTypeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $invalidServiceTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    public function mergeServiceTypes(
        ArrayObject $validServiceTypeTransfers,
        ArrayObject $invalidServiceTypeTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer>>
     */
    public function filterServiceTypesByValidity(
        ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
    ): array;
}
