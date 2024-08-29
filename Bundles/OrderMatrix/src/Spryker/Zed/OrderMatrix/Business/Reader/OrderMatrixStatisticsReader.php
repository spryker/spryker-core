<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Business\Reader;

use Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer;
use Spryker\Zed\OrderMatrix\Dependency\Client\OrderMatrixToStorageRedisClientInterface;
use Spryker\Zed\OrderMatrix\OrderMatrixConfig;

class OrderMatrixStatisticsReader implements OrderMatrixStatisticsReaderInterface
{
    /**
     * @param \Spryker\Zed\OrderMatrix\Dependency\Client\OrderMatrixToStorageRedisClientInterface $storageRedisClient
     * @param \Spryker\Zed\OrderMatrix\OrderMatrixConfig $orderMatrixConfig
     */
    public function __construct(
        protected OrderMatrixToStorageRedisClientInterface $storageRedisClient,
        protected OrderMatrixConfig $orderMatrixConfig
    ) {
    }

    /**
     * @return \Generated\Shared\Transfer\IndexedOrderMatrixResponseTransfer
     */
    public function getOrderMatrixStatistics(): IndexedOrderMatrixResponseTransfer
    {
        $storageKey = $this->orderMatrixConfig->getOrderMatrixStorageKey();
        $orderMatrices = $this->storageRedisClient->get($storageKey);
        if (!$orderMatrices) {
            return (new IndexedOrderMatrixResponseTransfer())
                ->setMatrices([]);
        }

        return (new IndexedOrderMatrixResponseTransfer())
            ->setMatrices($orderMatrices);
    }
}
