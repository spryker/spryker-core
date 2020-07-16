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
     * @phpstan-return array<string, mixed>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return array
     */
    public function provide(ArrayObject $itemTransfers): array;
}
