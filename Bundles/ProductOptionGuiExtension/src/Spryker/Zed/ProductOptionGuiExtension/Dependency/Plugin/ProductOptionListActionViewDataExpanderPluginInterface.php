<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionGuiExtension\Dependency\Plugin;

/**
 * Allows to expand view data of product option group list at ProductOption/ListController::indexAction().
 */
interface ProductOptionListActionViewDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands view data for product option groups list with new data.
     *
     * @api
     *
     * @phpstan-param array<mixed> $viewData
     *
     * @phpstan-return array<mixed>
     *
     * @param array $viewData
     *
     * @return array
     */
    public function expand(array $viewData): array;
}
