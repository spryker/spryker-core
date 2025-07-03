<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Business\Updater;

interface ItemReservationUpdaterInterface
{
    /**
     * @param string $orderReference
     *
     * @return void
     */
    public function updateDeletedItemsReservations(
        string $orderReference
    ): void;
}
