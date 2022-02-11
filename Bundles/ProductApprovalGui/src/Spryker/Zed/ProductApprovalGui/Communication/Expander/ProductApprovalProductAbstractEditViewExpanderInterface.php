<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Expander;

interface ProductApprovalProductAbstractEditViewExpanderInterface
{
    /**
     * @param array<mixed> $viewData
     *
     * @return array<mixed>
     */
    public function expand(array $viewData): array;
}
