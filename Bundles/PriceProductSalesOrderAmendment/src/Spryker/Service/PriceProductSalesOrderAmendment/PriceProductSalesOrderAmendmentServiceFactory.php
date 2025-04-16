<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\PriceProductSalesOrderAmendment\GroupKeyBuilder\OriginalSalesOrderItemPriceGroupKeyBuilder;
use Spryker\Service\PriceProductSalesOrderAmendment\GroupKeyBuilder\OriginalSalesOrderItemPriceGroupKeyBuilderInterface;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceResolver\OriginalSalesOrderItemPriceResolver;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceResolver\OriginalSalesOrderItemPriceResolverInterface;

/**
 * @method \Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig getConfig()
 */
class PriceProductSalesOrderAmendmentServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\PriceProductSalesOrderAmendment\GroupKeyBuilder\OriginalSalesOrderItemPriceGroupKeyBuilderInterface
     */
    public function createOriginalSalesOrderItemPriceGroupKeyBuilder(): OriginalSalesOrderItemPriceGroupKeyBuilderInterface
    {
        return new OriginalSalesOrderItemPriceGroupKeyBuilder(
            $this->getOriginalSalesOrderItemPriceGroupKeyExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Service\PriceProductSalesOrderAmendment\PriceResolver\OriginalSalesOrderItemPriceResolverInterface
     */
    public function createOriginalSalesOrderItemPriceResolver(): OriginalSalesOrderItemPriceResolverInterface
    {
        return new OriginalSalesOrderItemPriceResolver($this->getConfig());
    }

    /**
     * @return list<\Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface>
     */
    public function getOriginalSalesOrderItemPriceGroupKeyExpanderPlugins(): array
    {
        return $this->getProvidedDependency(PriceProductSalesOrderAmendmentDependencyProvider::PLUGINS_ORIGINAL_SALES_ORDER_ITEM_PRICE_GROUP_KEY_EXPANDER);
    }
}
