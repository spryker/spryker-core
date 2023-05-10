<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use ArrayObject;

interface ServicePointServiceServiceTypeExpanderInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    public function expandServicePointServiceTransfersWithServiceTypeRelations(
        ArrayObject $servicePointServiceTransfers
    ): ArrayObject;
}
