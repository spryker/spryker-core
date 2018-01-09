<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 */
class AbstractProductLabelStorageListener extends AbstractPlugin
{
    /**
     * @param array $productAbstractIds
     *
     * @return void
     */
    protected function publish(array $productAbstractIds)
    {
        $productLabels = $this->findProductLabelAbstractEntities($productAbstractIds);
        $groupedLabelsByProductAbstractId = [];
        foreach ($productLabels as $productLabel) {
            $groupedLabelsByProductAbstractId[$productLabel['fk_product_abstract']][] = $productLabel['fk_product_label'];
        }

        $spyProductAbstractLocalizedAttributeEntities = $this->findProductAbstractLocalizedEntities($productAbstractIds);
        $spyProductAbstractLabelStorageEntities = $this->findProductAbstractLabelStorageEntitiesByProductAbstractIds($productAbstractIds);

        $this->storeData($spyProductAbstractLocalizedAttributeEntities, $spyProductAbstractLabelStorageEntities, $groupedLabelsByProductAbstractId);
    }

    /**
     * @param array $spyProductAbstractLocalizedEntities
     * @param array $spyProductAbstractLabelStorageEntities
     * @param array $productLabelsIds
     *
     * @return void
     */
    protected function storeData(array $spyProductAbstractLocalizedEntities, array $spyProductAbstractLabelStorageEntities, array $productLabelsIds)
    {
        foreach ($spyProductAbstractLocalizedEntities as $spyProductAbstractLocalizedEntity) {
            $idProduct = $spyProductAbstractLocalizedEntity->getFkProductAbstract();
            $localeName = $spyProductAbstractLocalizedEntity->getLocale()->getLocaleName();
            if (isset($spyProductAbstractLabelStorageEntities[$idProduct][$localeName])) {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $productLabelsIds, $spyProductAbstractLabelStorageEntities[$idProduct][$localeName]);
            } else {
                $this->storeDataSet($spyProductAbstractLocalizedEntity, $productLabelsIds);
            }
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\Base\SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity
     * @param array $productLabelsIds
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage|null $spyProductAbstractLabelStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(SpyProductAbstractLocalizedAttributes $spyProductAbstractLocalizedEntity, array $productLabelsIds, SpyProductAbstractLabelStorage $spyProductAbstractLabelStorageEntity = null)
    {
        if ($spyProductAbstractLabelStorageEntity === null) {
            $spyProductAbstractLabelStorageEntity = new SpyProductAbstractLabelStorage();
        }

        if (empty($productLabelsIds[$spyProductAbstractLocalizedEntity->getFkProductAbstract()])) {
            if (!$spyProductAbstractLabelStorageEntity->isNew()) {
                $spyProductAbstractLabelStorageEntity->delete();
            }

            return;
        }

        $productAbstractLabelStorageTransfer = new ProductAbstractLabelStorageTransfer();
        $productAbstractLabelStorageTransfer->setIdProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $productAbstractLabelStorageTransfer->setProductLabelIds($productLabelsIds[$spyProductAbstractLocalizedEntity->getFkProductAbstract()]);

        $spyProductAbstractLabelStorageEntity->setFkProductAbstract($spyProductAbstractLocalizedEntity->getFkProductAbstract());
        $spyProductAbstractLabelStorageEntity->setData($productAbstractLabelStorageTransfer->toArray());
        $spyProductAbstractLabelStorageEntity->setStore($this->getStoreName());
        $spyProductAbstractLabelStorageEntity->setLocale($spyProductAbstractLocalizedEntity->getLocale()->getLocaleName());
        $spyProductAbstractLabelStorageEntity->save();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[]
     */
    protected function findProductLabelAbstractEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductLabelProductAbstractByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLocalizedEntities(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductAbstractLocalizedByIds($productAbstractIds)->find()->getData();
    }

    /**
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findProductAbstractLabelStorageEntitiesByProductAbstractIds(array $productAbstractIds)
    {
        $productAbstractLabelStorageEntities = $this->getQueryContainer()->queryProductAbstractLabelStorageByIds($productAbstractIds)->find();
        $productAbstractStorageLabelEntitiesByIdAndLocale = [];
        foreach ($productAbstractLabelStorageEntities as $productAbstractLabelStorageEntity) {
            $productAbstractStorageLabelEntitiesByIdAndLocale[$productAbstractLabelStorageEntity->getFkProductAbstract()][$productAbstractLabelStorageEntity->getLocale()] = $productAbstractLabelStorageEntity;
        }

        return $productAbstractStorageLabelEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
