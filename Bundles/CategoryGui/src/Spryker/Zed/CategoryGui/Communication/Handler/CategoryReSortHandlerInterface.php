<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Handler;

interface CategoryReSortHandlerInterface
{
    /**
     * @param string $categoryNodesData
     *
     * @return void
     */
    public function handle(string $categoryNodesData): void;
}
