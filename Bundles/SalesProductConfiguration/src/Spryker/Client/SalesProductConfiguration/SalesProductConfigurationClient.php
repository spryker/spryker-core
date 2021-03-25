<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationFactory getFactory()
 */
class SalesProductConfigurationClient extends AbstractClient implements SalesProductConfigurationClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemsWithProductConfiguration(array $itemTransfers, OrderTransfer $orderTransfer): array
    {
        return $this->getFactory()
            ->createItemExpander()
            ->expandItemsWithProductConfiguration($itemTransfers, $orderTransfer);
    }
}
