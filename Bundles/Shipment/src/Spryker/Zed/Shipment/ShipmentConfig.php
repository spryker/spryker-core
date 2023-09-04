<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Shipment\ShipmentConfig getSharedConfig()
 */
class ShipmentConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getShipmentExpenseType(): string
    {
        return $this->getSharedConfig()::SHIPMENT_EXPENSE_TYPE;
    }

    /**
     * Specification:
     * - If set to `true` a stack of {@link \Spryker\Zed\CalculationExtension\Dependency\Plugin\QuotePostRecalculatePluginStrategyInterface} will be executed after quote recalculation.
     * - Impacts {@link \Spryker\Zed\Shipment\Business\ShipmentFacade::expandQuoteWithShipmentGroups()} method.
     *
     * @api
     *
     * @return bool
     */
    public function shouldExecuteQuotePostRecalculationPlugins(): bool
    {
        return true;
    }
}
