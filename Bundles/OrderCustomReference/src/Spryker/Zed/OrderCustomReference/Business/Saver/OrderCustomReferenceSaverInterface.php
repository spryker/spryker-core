<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Saver;

interface OrderCustomReferenceSaverInterface
{
    /**
     * @param string $orderCustomReference
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function saveOrderCustomReference(string $orderCustomReference, int $idSalesOrder): void;
}
