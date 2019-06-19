<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentFormTransfer;

interface ShipmentFormDataProviderInterface
{
    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentFormTransfer
     */
    public function getData(int $idSalesOrder, ?int $idSalesShipment = null): ShipmentFormTransfer;

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array[]
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array;
}
