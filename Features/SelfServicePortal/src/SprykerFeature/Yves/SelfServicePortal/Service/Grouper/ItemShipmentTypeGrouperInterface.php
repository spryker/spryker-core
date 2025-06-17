<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Grouper;

interface ItemShipmentTypeGrouperInterface
{
    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, array<string, list<\Generated\Shared\Transfer\ItemTransfer>>>
     */
    public function groupItemsByShipmentType(iterable $itemTransfers): array;
}
