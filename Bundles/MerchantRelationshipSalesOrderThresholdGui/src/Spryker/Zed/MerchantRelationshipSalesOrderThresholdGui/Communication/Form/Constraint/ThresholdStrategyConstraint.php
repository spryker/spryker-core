<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class ThresholdStrategyConstraint extends Constraint
{
    public const OPTION_SALES_ORDER_THRESHOLD_FORM_EXPANDER_PLUGINS = 'salesOrderThresholdFormExpanderPlugins';

    /**
     * @var \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]|\Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface[]
     */
    protected $salesOrderThresholdFormExpanderPlugins;

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]|\Spryker\Zed\MerchantRelationshipSalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormFieldDependenciesPluginInterface[]
     */
    public function getSalesOrderThresholdFormExpanderPlugins(): array
    {
        return $this->salesOrderThresholdFormExpanderPlugins;
    }
}
