<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ShipmentFormTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentFormDefaultDataProviderInterface
{
    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array
     */
    public function getDefaultFormFields(int $idSalesOrder, ?int $idSalesShipment = null): array;

    /**
     * @param int $idSalesOrder
     * @param int|null $idSalesShipment
     *
     * @return array[]
     */
    public function getOptions(int $idSalesOrder, ?int $idSalesShipment = null): array;

    /**
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer|null
     */
    public function findShipmentById(int $idSalesShipment): ?ShipmentTransfer;

    /**
     * @param int|null $idSalesShipment
     *
     * @return int[]
     */
    public function getShipmentSelectedItemsIds(?int $idSalesShipment): array;

    /**
     * @param array $formData
     *
     * @return \Generated\Shared\Transfer\ShipmentFormTransfer
     */
    public function mapFormDataToShipmentFormTransfer(array $formData): ShipmentFormTransfer;
}
