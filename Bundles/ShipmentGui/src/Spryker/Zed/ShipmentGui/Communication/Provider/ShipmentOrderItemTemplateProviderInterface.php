<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Provider;

use ArrayObject;

interface ShipmentOrderItemTemplateProviderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, mixed>
     */
    public function provide(ArrayObject $itemTransfers): array;
}
