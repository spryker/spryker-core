<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesOrderAmendment;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\SalesOrderAmendment\GroupKeyBuilder\OriginalSalesOrderItemGroupKeyBuilder;
use Spryker\Service\SalesOrderAmendment\GroupKeyBuilder\OriginalSalesOrderItemGroupKeyBuilderInterface;

class SalesOrderAmendmentServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\SalesOrderAmendment\GroupKeyBuilder\OriginalSalesOrderItemGroupKeyBuilderInterface
     */
    public function createOriginalSalesOrderItemGroupKeyBuilder(): OriginalSalesOrderItemGroupKeyBuilderInterface
    {
        return new OriginalSalesOrderItemGroupKeyBuilder(
            $this->getOriginalSalesOrderItemGroupKeyExpanderPlugins(),
        );
    }

    /**
     * @return list<\Spryker\Service\SalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemGroupKeyExpanderPluginInterface>
     */
    public function getOriginalSalesOrderItemGroupKeyExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SalesOrderAmendmentDependencyProvider::PLUGINS_ORIGINAL_SALES_ORDER_ITEM_GROUP_KEY_EXPANDER);
    }
}
