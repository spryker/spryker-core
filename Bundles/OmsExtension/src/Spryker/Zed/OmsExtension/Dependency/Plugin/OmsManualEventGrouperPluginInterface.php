<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OmsExtension\Dependency\Plugin;

interface OmsManualEventGrouperPluginInterface
{
    /**
     * Specification:
     *  - Groups manual events.
     *
     * @api
     *
     * @param array $events
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return array
     */
    public function group(array $events, iterable $orderItemTransfers): array;
}
