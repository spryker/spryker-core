<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Plugin\ProductCategoryFilterGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategoryFilterGuiExtension\Dependency\Plugin\ProductCategoryListActionViewDataExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreGui\StoreGuiConfig getConfig()
 */
class StoreProductCategoryListActionViewDataExpanderPlugin extends AbstractPlugin implements ProductCategoryListActionViewDataExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands view data for list of product categories with stores data.
     *
     * @api
     *
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array
    {
        return $this->getFactory()
            ->createStoreListDataExpander()
            ->expandData($viewData);
    }
}
