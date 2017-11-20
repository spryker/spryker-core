<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Util;

interface ReservationInterface
{
    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateReservationQuantity($sku);

    /**
     * @param string $sku
     *
     * @return int
     */
    public function sumReservedProductQuantitiesForSku($sku);

    /**
     * @param string $sku
     *
     * @return int
     */
    public function getOmsReservedProductQuantityForSku($sku);
}
