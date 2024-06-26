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
 * @deprecated {@link \Spryker\Zed\ProductPageSearch\Communication\Plugin\Synchronization\ProductConcretePageSynchronizationDataBulkPlugin} instead.
 *
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductConcretePageSynchronizationDataPlugin extends AbstractPlugin implements SynchronizationDataRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $ids Concrete product IDs.
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getData(array $ids = []): array
    {
        $synchronizationDataTransfers = [];
        $productConcretePageSearchTransfers = $this->getProductConcretePageSearchTransfers($ids);

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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcretePageSearchTransfer>
     */
    protected function getProductConcretePageSearchTransfers(array $productIds): array
    {
        if (!$productIds) {
            $productIds = $this->getAllProductIds();
        }

        return $this->getFacade()->getProductConcretePageSearchTransfersByProductIds($productIds);
    }

    /**
     * @return array<int>
     */
    protected function getAllProductIds(): array
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $productIds */
        $productIds = SpyProductQuery::create()
            ->select([SpyProductTableMap::COL_ID_PRODUCT])
            ->find();

        return $productIds->toArray();
    }
}
