<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 */
class AbstractProductLabelDictionaryStorageListener extends AbstractPlugin
{

    /**
     * @return void
     */
    protected function publish()
    {
        $spyProductLabelLocalizedAttributeEntities = $this->findProductLabelLocalizedEntities();
        $spyProductLabelLocalizedDictionaries = [];
        foreach ($spyProductLabelLocalizedAttributeEntities as $spyProductLabelLocalizedAttributeEntity) {
            $spyProductLabelLocalizedDictionaries[$spyProductLabelLocalizedAttributeEntity['SpyLocale']['locale_name']][] = $spyProductLabelLocalizedAttributeEntity;
        }

        $spyProductLabelDictionaryStorageEntities = $this->findProductLabelDictionaryStorageEntities();
        $this->storeData($spyProductLabelLocalizedDictionaries, $spyProductLabelDictionaryStorageEntities);
    }

    /**
     * @return void
     */
    protected function unpublish()
    {
        $spyProductStorageEntities = $this->findProductLabelDictionaryStorageEntities();
        foreach ($spyProductStorageEntities as $spyProductStorageEntity) {
            $spyProductStorageEntity->delete();
        }
    }

    /**
     * @param array $spyProductLabelLocalizedDictionaries
     * @param array $spyProductLabelStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyProductLabelLocalizedDictionaries, array $spyProductLabelStorageEntities)
    {
        foreach ($spyProductLabelLocalizedDictionaries as $localeName => $spyProductLabelLocalizedDictionary) {
            if (isset($spyProductLabelStorageEntities[$localeName]))  {
                $this->storeDataSet($spyProductLabelLocalizedDictionary, $spyProductLabelStorageEntities[$localeName], $localeName);
            } else {
                $this->storeDataSet($spyProductLabelLocalizedDictionary, null, $localeName);
            }
        }
    }

    /**
     * @param array $spyProductLabelLocalizedDictionary
     * @param \Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage|null $spyProductLabelStorageEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function storeDataSet(array $spyProductLabelLocalizedDictionary, SpyProductLabelDictionaryStorage $spyProductLabelStorageEntity = null, $localeName)
    {
        if ($spyProductLabelStorageEntity === null) {
            $spyProductLabelStorageEntity = new SpyProductLabelDictionaryStorage();
        }

        $spyProductLabelLocalizedDictionary = $this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($spyProductLabelLocalizedDictionary);
        $spyProductLabelStorageEntity->setData($spyProductLabelLocalizedDictionary);
        $spyProductLabelStorageEntity->setStore($this->getStoreName());
        $spyProductLabelStorageEntity->setLocale($localeName);
        $spyProductLabelStorageEntity->save();
    }

    /**
     * @return array
     */
    protected function findProductLabelLocalizedEntities()
    {
        return $this->getQueryContainer()->queryProductLabelLocalizedAttributes()->find()->getData();
    }

    /**
     * @return array
     */
    protected function findProductLabelDictionaryStorageEntities()
    {
        $productAbstractStorageEntities = $this->getQueryContainer()->queryProductLabelDictionaryStorage()->find();
        $productAbstractStorageEntitiesByIdAndLocale = [];
        foreach ($productAbstractStorageEntities as $productAbstractStorageEntity) {
            $productAbstractStorageEntitiesByIdAndLocale[$productAbstractStorageEntity->getLocale()] = $productAbstractStorageEntity;
        }

        return $productAbstractStorageEntitiesByIdAndLocale;
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }

}
