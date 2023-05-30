<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ServiceCollectionResponseTransfer;

interface ServiceFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $validServiceTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $invalidServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>
     */
    public function mergeServices(
        ArrayObject $validServiceTransfers,
        ArrayObject $invalidServiceTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer>>
     */
    public function filterServicesByValidity(
        ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
    ): array;
}
