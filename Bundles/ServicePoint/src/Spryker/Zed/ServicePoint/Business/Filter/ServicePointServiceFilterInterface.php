<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer;

interface ServicePointServiceFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $validServicePointServiceTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $invalidServicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    public function mergeServicePointServices(
        ArrayObject $validServicePointServiceTransfers,
        ArrayObject $invalidServicePointServiceTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>>
     */
    public function filterServicePointServicesByValidity(
        ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
    ): array;
}
