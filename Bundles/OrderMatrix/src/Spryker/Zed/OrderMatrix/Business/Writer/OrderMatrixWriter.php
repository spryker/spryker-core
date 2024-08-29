<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrix\Business\Writer;

use Spryker\Zed\OrderMatrix\Business\Indexer\OrderMatrixIndexerInterface;
use Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixReaderInterface;
use Spryker\Zed\OrderMatrix\Dependency\Client\OrderMatrixToStorageRedisClientInterface;
use Spryker\Zed\OrderMatrix\Dependency\Service\OrderMatrixToUtilEncodingServiceInterface;
use Spryker\Zed\OrderMatrix\OrderMatrixConfig;

class OrderMatrixWriter implements OrderMatrixWriterInterface
{
    /**
     * @param \Spryker\Zed\OrderMatrix\Business\Reader\OrderMatrixReaderInterface $orderMatrixReader
     * @param \Spryker\Zed\OrderMatrix\Business\Indexer\OrderMatrixIndexerInterface $orderMatrixGrouper
     * @param \Spryker\Zed\OrderMatrix\Dependency\Client\OrderMatrixToStorageRedisClientInterface $storageRedisClient
     * @param \Spryker\Zed\OrderMatrix\Dependency\Service\OrderMatrixToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\OrderMatrix\OrderMatrixConfig $orderMatrixConfig
     */
    public function __construct(
        protected OrderMatrixReaderInterface $orderMatrixReader,
        protected OrderMatrixIndexerInterface $orderMatrixGrouper,
        protected OrderMatrixToStorageRedisClientInterface $storageRedisClient,
        protected OrderMatrixToUtilEncodingServiceInterface $utilEncodingService,
        protected OrderMatrixConfig $orderMatrixConfig
    ) {
    }

    /**
     * @return void
     */
    public function writeOrderMatrix(): void
    {
        $orderMatrices = [];
        $orderMatrixCollectionTransfers = $this->orderMatrixReader->getOrderMatrix();

        foreach ($orderMatrixCollectionTransfers as $orderMatrixCollectionTransfer) {
            $orderMatrices = $this->orderMatrixGrouper->getOrderMatrixIndexedByStateProcessAndDateRange($orderMatrixCollectionTransfer, $orderMatrices);
        }

        $storageKey = $this->orderMatrixConfig->getOrderMatrixStorageKey();
        /** @var string $encodedOrderMatrices */
        $encodedOrderMatrices = $this->utilEncodingService->encodeJson($orderMatrices);
        $this->storageRedisClient->set($storageKey, $encodedOrderMatrices);
    }
}
