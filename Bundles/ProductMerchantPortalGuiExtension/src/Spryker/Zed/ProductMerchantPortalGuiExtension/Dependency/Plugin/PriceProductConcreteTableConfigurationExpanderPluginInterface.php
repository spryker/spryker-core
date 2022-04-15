<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin;

use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;

/**
 * Implement this plugin interface to expand price product concrete table configuration.
 */
interface PriceProductConcreteTableConfigurationExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands price product concrete table configuration.
     *
     * @api
     *
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function expand(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface;
}
