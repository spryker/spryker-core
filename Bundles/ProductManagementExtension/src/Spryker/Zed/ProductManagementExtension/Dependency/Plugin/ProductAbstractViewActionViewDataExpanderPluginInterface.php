<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

/**
 * Allows to expand view data for abstract product at ProductManagement/ViewController::indexAction().
 */
interface ProductAbstractViewActionViewDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands view data for abstract product with new data.
     *
     * @api
     *
     * @phpstan-param array<string, mixed> $viewData
     *
     * @phpstan-return array<string, mixed>
     *
     * @param array $viewData
     *
     * @return array
     */
    public function expand(array $viewData): array;
}
