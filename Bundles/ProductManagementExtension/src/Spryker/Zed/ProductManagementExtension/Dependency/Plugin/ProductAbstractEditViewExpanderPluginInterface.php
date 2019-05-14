<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

interface ProductAbstractEditViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands view data for edit product abstract page.
     *
     * @api
     *
     * @param array $viewData
     *
     * @return array
     */
    public function expand(array $viewData): array;
}
