<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Business\Storage;

use Generated\Shared\Transfer\ProductSearchAttributeConditionsTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Generated\Shared\Transfer\ProductSearchConfigStorageTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorage;
use Spryker\Zed\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchFacadeInterface;
use Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface;

class ProductSearchConfigStorageWriter implements ProductSearchConfigStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchFacadeInterface
     */
    protected $productSearchFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\ProductSearchConfig
     */
    protected $productSearchConfig;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchFacadeInterface $productSearchFacade
     * @param \Spryker\Zed\ProductSearch\ProductSearchConfig $productSearchConfig
     * @param bool $isSendingToQueue
     */
    public function __construct(
        ProductSearchConfigStorageQueryContainerInterface $queryContainer,
        ProductSearchConfigStorageToProductSearchFacadeInterface $productSearchFacade,
        ProductSearchConfig $productSearchConfig,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->productSearchFacade = $productSearchFacade;
        $this->productSearchConfig = $productSearchConfig;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @return void
     */
    public function publish()
    {
        $productSearchConfigStorageTransfer = new ProductSearchConfigStorageTransfer();
        $availableProductSearchFilterConfigs = $this->productSearchConfig->getAvailableProductSearchFilterConfigs();

        $sortTransfer = (new SortTransfer())
            ->setField(ProductSearchAttributeTransfer::POSITION)
            ->setIsAscending(true);
        $productSearchAttributeConditionsTransfer = (new ProductSearchAttributeConditionsTransfer())
            ->setWithLocalizedAttributes(false);
        $productSearchAttributeCriteriaTransfer = (new ProductSearchAttributeCriteriaTransfer())
            ->addSort($sortTransfer)
            ->setProductSearchAttributeConditions($productSearchAttributeConditionsTransfer);
        $productSearchAttributeCollectionTransfer = $this->productSearchFacade->getProductSearchAttributeCollection($productSearchAttributeCriteriaTransfer);

        foreach ($productSearchAttributeCollectionTransfer->getProductSearchAttributes() as $productSearchAttributeTransfer) {
            $facetConfigTransfer = clone $availableProductSearchFilterConfigs[$productSearchAttributeTransfer->getFilterType()];

            $facetConfigTransfer
                ->setName($productSearchAttributeTransfer->getKey())
                ->setParameterName($productSearchAttributeTransfer->getKey());

            $productSearchConfigStorageTransfer->addFacetConfig($facetConfigTransfer);
        }

        $spyProductSearchConfigStorageEntity = $this->findProductSearchConfigDictionaryStorageEntity();
        $this->storeDataSet($productSearchConfigStorageTransfer, $spyProductSearchConfigStorageEntity);
    }

    /**
     * @return void
     */
    public function unpublish()
    {
        $spyProductStorageEntity = $this->findProductSearchConfigDictionaryStorageEntity();
        $spyProductStorageEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchConfigStorageTransfer $searchConfigExtensionTransfer
     * @param \Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorage|null $spyProductSearchConfigStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(
        ProductSearchConfigStorageTransfer $searchConfigExtensionTransfer,
        ?SpyProductSearchConfigStorage $spyProductSearchConfigStorageEntity = null
    ) {
        if ($spyProductSearchConfigStorageEntity === null) {
            $spyProductSearchConfigStorageEntity = new SpyProductSearchConfigStorage();
        }

        $spyProductSearchConfigStorageEntity->setData($searchConfigExtensionTransfer->toArray());
        $spyProductSearchConfigStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $spyProductSearchConfigStorageEntity->save();
    }

    /**
     * @return \Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorage|null
     */
    protected function findProductSearchConfigDictionaryStorageEntity()
    {
        return $this->queryContainer->queryProductSearchConfigStorage()->findOne();
    }
}
