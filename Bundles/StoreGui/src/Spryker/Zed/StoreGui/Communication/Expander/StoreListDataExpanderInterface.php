<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Expander;

interface StoreListDataExpanderInterface
{
    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expandData(array $viewData): array;
}
