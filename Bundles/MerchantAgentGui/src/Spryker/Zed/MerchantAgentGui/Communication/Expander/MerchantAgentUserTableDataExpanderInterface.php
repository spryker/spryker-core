<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication\Expander;

interface MerchantAgentUserTableDataExpanderInterface
{
    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function expandData(array $item): array;
}
