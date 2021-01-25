<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGuiExtension\Dependency\Plugin;

interface MerchantTableActionExpanderPluginInterface
{
    /**
     * Specification:
     * - Prepares ButtonTransfer[] for using in actions list
     *
     * @api
     *
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function expand(array $item): array;
}
