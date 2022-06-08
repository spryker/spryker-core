<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Plugin\ProductMerchantPortalGui;

use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductAbstractTableConfigurationExpanderPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\PriceProductMerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\PriceProductMerchantRelationshipMerchantPortalGuiConfig getConfig()
 */
class MerchantRelationshipPriceProductAbstractTableConfigurationExpanderPlugin extends AbstractPlugin implements PriceProductAbstractTableConfigurationExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Expands price product abstract table configuration with Merchant Relationship column.
     *  - Overrides `delete` and `save` url configuration for Merchant Relationship prices to include `idMerchantRelationship` parameter.
     *  - Disables volume price column for the merchant prices.
     *
     * @api
     *
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function expand(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        return $this->getFactory()->createPriceProductAbstractTableConfigurationExpander()->expand($guiTableConfigurationBuilder);
    }
}
