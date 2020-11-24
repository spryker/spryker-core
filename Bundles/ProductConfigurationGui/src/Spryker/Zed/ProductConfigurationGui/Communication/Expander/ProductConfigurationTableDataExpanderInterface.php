<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Communication\Expander;

interface ProductConfigurationTableDataExpanderInterface
{
    /**
     * @param array $item
     *
     * @return array
     */
    public function expandProductItemWithProductConfigurationType(array $item): array;
}
