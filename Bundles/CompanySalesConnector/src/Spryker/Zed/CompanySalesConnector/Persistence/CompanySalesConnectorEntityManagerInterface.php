<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Persistence;

interface CompanySalesConnectorEntityManagerInterface
{
    /**
     * @param int $idSalesOrder
     * @param string $companyUuid
     *
     * @return void
     */
    public function updateOrderCompanyUuid(int $idSalesOrder, string $companyUuid): void;
}
