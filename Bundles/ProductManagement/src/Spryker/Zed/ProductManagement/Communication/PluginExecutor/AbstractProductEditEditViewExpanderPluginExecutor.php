<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\PluginExecutor;

class AbstractProductEditEditViewExpanderPluginExecutor implements AbstractProductEditViewExpanderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditViewExpanderPluginInterface[]
     */
    protected $abstractProductEditViewExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditViewExpanderPluginInterface[] $abstractProductEditViewExpanderPlugins
     */
    public function __construct(array $abstractProductEditViewExpanderPlugins)
    {
        $this->abstractProductEditViewExpanderPlugins = $abstractProductEditViewExpanderPlugins;
    }

    /**
     * @param array $viewData
     *
     * @return array
     */
    public function expandEditAbstractProductViewData(array $viewData): array
    {
        foreach ($this->abstractProductEditViewExpanderPlugins as $plugin) {
            $viewData = $plugin->expand($viewData);
        }

        return $viewData;
    }
}
