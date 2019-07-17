<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Synchronization;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductConcretePageSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function hasStore(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getData(array $productConcreteIds = []): array
    {
        $synchronizationDataTransfers = [];
        $productConcretePageSearchTransfers = $this->getProductConcretePageSearchTransfers($productConcreteIds);

        /** @var \Generated\Shared\Transfer\ProductConcretePageSearchTransfer $productConcretePageSearchTransfer */
        foreach ($productConcretePageSearchTransfers as $productConcretePageSearchTransfer) {
            $synchronizationDataTransfer = new SynchronizationDataTransfer();
            $synchronizationDataTransfer->setData($productConcretePageSearchTransfer->getData());
            $synchronizationDataTransfer->setKey($productConcretePageSearchTransfer->getKey());
            $synchronizationDataTransfer->setStore($productConcretePageSearchTransfer->getStore());
            $synchronizationDataTransfers[] = $synchronizationDataTransfer;
        }

        return $synchronizationDataTransfers;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getParams(): array
    {
        return ['type' => 'page'];
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return ProductPageSearchConstants::PRODUCT_SYNC_SEARCH_QUEUE;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSynchronizationQueuePoolName(): ?string
    {
        return $this->getFactory()->getConfig()->getProductPageSynchronizationPoolName();
    }

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcretePageSearchTransfer[]
     */
    protected function getProductConcretePageSearchTransfers(array $productIds): array
    {
        if (empty($productIds)) {
            $productIds = $this->getAllProductIds();
        }

        return $this->getFacade()->getProductConcretePageSearchTransfersByProductIds($productIds);
    }

    /**
     * @return int[]
     */
    protected function getAllProductIds(): array
    {
        return SpyProductQuery::create()
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find()
            ->toArray();
    }
}
