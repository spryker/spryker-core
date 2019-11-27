<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockExtension\Dependency\Plugin;

interface StockUpdateHandlerPluginInterface
{
    /**
     * Specification:
     *  - This plugin handles all necessary events related to stock updates, like Availability.
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function handle($sku);
}
