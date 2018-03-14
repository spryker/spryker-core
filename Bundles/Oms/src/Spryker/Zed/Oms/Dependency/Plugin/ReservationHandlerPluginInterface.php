<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin;

interface ReservationHandlerPluginInterface
{
    /**
     *
     * Specification:
     *  - This plugin handles all necessary events related to reservation updates, such as updating Stock, Availability and etc.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku);
}
