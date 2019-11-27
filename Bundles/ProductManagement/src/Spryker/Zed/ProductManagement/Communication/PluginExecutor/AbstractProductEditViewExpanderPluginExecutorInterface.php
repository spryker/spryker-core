<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\PluginExecutor;

interface AbstractProductEditViewExpanderPluginExecutorInterface
{
    /**
     * @param array $viewData
     *
     * @return array
     */
    public function expandEditAbstractProductViewData(array $viewData): array;
}
