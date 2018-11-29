<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Communication\Form\DataProvider\ThresholdGroup;

abstract class AbstractMerchantRelationshipThresholdDataProvider
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(array $formExpanderPlugins)
    {
        $this->formExpanderPlugins = $formExpanderPlugins;
    }
}
