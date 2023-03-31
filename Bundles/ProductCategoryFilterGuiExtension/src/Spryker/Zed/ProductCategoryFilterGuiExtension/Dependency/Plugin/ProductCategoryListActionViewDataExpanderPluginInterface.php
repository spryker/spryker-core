<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGuiExtension\Dependency\Plugin;

/**
 * Allows to expand view data for list of product categories at ProductCategoryFilterGui/IndexController::indexAction().
 */
interface ProductCategoryListActionViewDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands view data for list of product categories with new data.
     *
     * @api
     *
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array;
}
