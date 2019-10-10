<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGuiExtension\Dependency\Plugin;

interface MerchantTableDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands table row data.
     *
     * @api
     *
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array;
}
