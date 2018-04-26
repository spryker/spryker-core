<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductSearchConfigStorageTransfer;
use Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSearchConfigStorage\Communication\ProductSearchConfigStorageCommunicationFactory getFactory()
 */
class AbstractProductSearchConfigStorageListener extends AbstractPlugin
{
    /**
     * @return void
     */
    protected function publish()
    {
        $productSearchConfigStorageTransfer = new ProductSearchConfigStorageTransfer();
        $availableProductSearchFilterConfigs = $this->getFactory()->getProductSearchConfig()->getAvailableProductSearchFilterConfigs();
        $productSearchAttributeTransfers = $this->getFactory()->getProductSearchFacade()->getProductSearchAttributeList();

        foreach ($productSearchAttributeTransfers as $productSearchAttributeTransfer) {
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
    protected function unpublish()
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
    protected function storeDataSet(ProductSearchConfigStorageTransfer $searchConfigExtensionTransfer, ?SpyProductSearchConfigStorage $spyProductSearchConfigStorageEntity = null)
    {
        if ($spyProductSearchConfigStorageEntity === null) {
            $spyProductSearchConfigStorageEntity = new SpyProductSearchConfigStorage();
        }

        $spyProductSearchConfigStorageEntity->setData($searchConfigExtensionTransfer->toArray());
        $spyProductSearchConfigStorageEntity->setStore($this->getStoreName());
        $spyProductSearchConfigStorageEntity->save();
    }

    /**
     * @return \Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorage
     */
    protected function findProductSearchConfigDictionaryStorageEntity()
    {
        return $this->getQueryContainer()->queryProductSearchConfigStorage()->findOne();
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
