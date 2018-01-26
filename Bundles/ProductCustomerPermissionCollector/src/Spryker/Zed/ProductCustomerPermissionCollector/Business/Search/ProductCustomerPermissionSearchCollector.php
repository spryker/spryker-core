<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermissionCollector\Business\Search;

use Generated\Shared\Search\CustomerPageIndexMap;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Collector\Business\Collector\Search\AbstractConfigurableSearchPropelCollector;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig;
use Spryker\Zed\ProductCustomerPermissionCollector\Persistence\Search\Propel\ProductCustomerPermissionSearchCollectorQuery;

class ProductCustomerPermissionSearchCollector extends AbstractConfigurableSearchPropelCollector
{
    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $productAbstractKey = $this->generateKeyForProduct($collectItemData[ProductCustomerPermissionSearchCollectorQuery::FIELD_FK_PRODUCT_ABSTRACT], $this->locale->getLocaleName());

        $result = [
            CustomerPageIndexMap::ID_CUSTOMER => $collectItemData[ProductCustomerPermissionSearchCollectorQuery::FIELD_FK_CUSTOMER],
            'parent' => $productAbstractKey,
        ];

        $result = $this->addExtraCollectorFields($result, $collectItemData);

        return $result;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return string
     */
    protected function generateKeyForProduct($idProductAbstract, $localeName)
    {
        $keyParts = $this->getKeyPartsForProduct($idProductAbstract, $localeName);

        return $this->escapeKey(implode($this->keySeparator, $keyParts));
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    protected function getKeyPartsForProduct($idProductAbstract, $localeName)
    {
        return [
            Store::getInstance()->getStoreName(),
            $localeName,
            $this->getBundleName(),
            ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT . '.' . $idProductAbstract,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected function getCollectorConfiguration()
    {
        $searchCollectorConfigurationTransfer = new SearchCollectorConfigurationTransfer();
        $searchCollectorConfigurationTransfer->setTypeName(ProductCustomerPermissionConfig::ELASTICSEARCH_INDEX_TYPE_NAME);

        return $searchCollectorConfigurationTransfer;
    }

    /**
     * @param array $result
     * @param array $collectItemData
     *
     * @return array
     */
    protected function addExtraCollectorFields(array $result, array $collectItemData)
    {
        $result[CollectorConfig::COLLECTOR_TOUCH_ID] = (int)$collectItemData[CollectorConfig::COLLECTOR_TOUCH_ID];
        $result[CollectorConfig::COLLECTOR_RESOURCE_ID] = (int)$collectItemData[CollectorConfig::COLLECTOR_RESOURCE_ID];
        $result[CollectorConfig::COLLECTOR_SEARCH_KEY] = $collectItemData[CollectorConfig::COLLECTOR_SEARCH_KEY];

        return $result;
    }
}
