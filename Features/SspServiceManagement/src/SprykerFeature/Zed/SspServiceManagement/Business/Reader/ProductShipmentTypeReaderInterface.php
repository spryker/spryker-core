<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business\Reader;

interface ProductShipmentTypeReaderInterface
{
    /**
     * @param list<int> $productConcreteIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    public function getShipmentTypesGroupedByIdProductConcrete(array $productConcreteIds): array;
}
