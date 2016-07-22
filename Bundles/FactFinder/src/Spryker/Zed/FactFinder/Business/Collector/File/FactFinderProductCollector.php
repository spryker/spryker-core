<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\FactFinder\Business\Collector\File;

use Pyz\Zed\Collector\Business\Storage\ProductCollector as StorageProductCollector;
use Pyz\Zed\Collector\CollectorConfig;

class FactFinderProductCollector extends StorageProductCollector
{

    const ABSTRACT_ATTRIBUTES_LONG_DESCRIPTION = 'long_description';
    const ABSTRACT_ATTRIBUTES_IMAGE_SMALL = 'image_small';

    /**
     * @var \Pyz\Zed\Collector\CollectorConfig
     */
    protected $config;

    /**
     * @param \Pyz\Zed\Collector\CollectorConfig $config
     *
     * @return void
     */
    public function setConfig(CollectorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return \Pyz\Zed\Collector\CollectorConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $abstractAttributes = $this->getAbstractAttributes($collectItemData);
        $generatedCategories = $this->generateCategories($collectItemData[CollectorConfig::COLLECTOR_RESOURCE_ID]);
        return [
            'ID' => $collectItemData[self::ABSTRACT_SKU],
            'Name' => $collectItemData[self::ABSTRACT_NAME],
            'description' => $abstractAttributes[self::ABSTRACT_ATTRIBUTES_LONG_DESCRIPTION],
            'ProductURL' =>  $this->getConfig()->getHostYves() . $collectItemData[self::ABSTRACT_URL],
//            'ImageURL' => $this->getConfig()->getHostYves() . $abstractAttributes[self::ABSTRACT_ATTRIBUTES_IMAGE_SMALL],
            'Price' => $this->getPriceBySku($collectItemData[self::ABSTRACT_SKU]),
            'Stock' =>  (int)$collectItemData[self::QUANTITY],
            'Product Category' => implode(' ˆˆ ', array_keys($generatedCategories)),
        ];
    }

    /**
     * @param string $sku
     *
     * @return float
     */
    protected function getPriceBySku($sku)
    {
        return $this->priceFacade->getPriceBySku($sku) / 100;
    }

}
