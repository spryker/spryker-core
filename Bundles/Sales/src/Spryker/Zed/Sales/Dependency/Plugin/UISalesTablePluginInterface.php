<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Plugin;

interface UISalesTablePluginInterface
{
    /**
     * Specifications:
     * - Get item inside foreach in AbstractTable::formatQueryData(). And update it.
     *
     * @api
     *
     * @param array $item
     *
     * @return array $item
     */
    public function formatQueryLine(array $item): array;
}
