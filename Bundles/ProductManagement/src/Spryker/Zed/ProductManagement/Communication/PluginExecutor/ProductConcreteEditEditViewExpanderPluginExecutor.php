<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\PluginExecutor;

class ProductConcreteEditEditViewExpanderPluginExecutor implements ProductConcreteEditViewExpanderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditViewExpanderPluginInterface[]
     */
    protected $productConcreteEditViewExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteEditViewExpanderPluginInterface[] $productConcreteEditViewExpanderPlugins
     */
    public function __construct(array $productConcreteEditViewExpanderPlugins)
    {
        $this->productConcreteEditViewExpanderPlugins = $productConcreteEditViewExpanderPlugins;
    }

    /**
     * @param array $viewData
     *
     * @return array
     */
    public function expandEditVariantViewData(array $viewData): array
    {
        foreach ($this->productConcreteEditViewExpanderPlugins as $plugin) {
            $viewData = $plugin->expand($viewData);
        }

        return $viewData;
    }
}
