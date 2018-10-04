<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\ProductConcreteReader;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface;
use Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
{
    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\QuickOrder\Dependency\Client\QuickOrderToLocaleClientInterface $localeClient
     */
    public function __construct(QuickOrderToProductStorageClientInterface $productStorageClient, QuickOrderToLocaleClientInterface $localeClient)
    {
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    public function findProductConcretesByQuickOrderItemTransfers(array $quickOrderItemTransfers): array
    {
        return $this->findProductConcreteStorageTransfers($quickOrderItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    protected function findProductConcreteStorageTransfers(array $quickOrderItemTransfers): array
    {
        $productConcreteStorageTransfers = [];

        foreach ($quickOrderItemTransfers as $quickOrderItemTransfer) {
            if ($quickOrderItemTransfer->getIdProductConcrete() !== null) {
                $productConcreteStorageTransfer = $this->findProductConcreteStorageData($quickOrderItemTransfer);

                if ($productConcreteStorageTransfer !== null) {
                    $productConcreteStorageTransfers[$quickOrderItemTransfer->getIdProductConcrete()] = $productConcreteStorageTransfer;
                }
            }
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer|null
     */
    protected function findProductConcreteStorageData(QuickOrderItemTransfer $quickOrderItemTransfer): ?ProductConcreteStorageTransfer
    {
        $productConcreteStorageData = $this->productStorageClient->findProductConcreteStorageData(
            $quickOrderItemTransfer->getIdProductConcrete(),
            $this->localeClient->getCurrentLocale()
        );

        if ($productConcreteStorageData === null) {
            return $productConcreteStorageData;
        }

        return $this->createProductConcreteStorageTransfer($productConcreteStorageData);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer
     */
    protected function createProductConcreteStorageTransfer(array $data): ProductConcreteStorageTransfer
    {
        return (new ProductConcreteStorageTransfer())->fromArray($data, true);
    }
}
