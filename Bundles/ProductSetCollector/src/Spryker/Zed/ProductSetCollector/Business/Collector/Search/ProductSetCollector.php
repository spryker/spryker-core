<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector\Business\Collector\Search;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\ProductSet\ProductSetConfig;
use Spryker\Zed\Collector\Business\Collector\Search\AbstractSearchPropelCollector;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;
use Spryker\Zed\Search\Business\SearchFacadeInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

class ProductSetCollector extends AbstractSearchPropelCollector
{

    /**
     * @var ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @var \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface
     */
    protected $productSetPageMapPlugin;

    /**
     * @var \Spryker\Zed\Search\Business\SearchFacadeInterface
     */
    protected $searchFacade;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $productSetPageMapPlugin
     * @param \Spryker\Zed\Search\Business\SearchFacadeInterface $searchFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        PageMapInterface $productSetPageMapPlugin,
        SearchFacadeInterface $searchFacade // FIXME: use bridge
    ) {
        parent::__construct($utilDataReaderService);

        $this->productSetPageMapPlugin = $productSetPageMapPlugin;
        $this->searchFacade = $searchFacade;
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $result = $this
            ->searchFacade
            ->transformPageMapToDocument($this->productSetPageMapPlugin, $collectItemData, $this->locale);

        $result = $this->addExtraCollectorFields($result, $collectItemData);

        return $result;
    }

    /**
     * @param array $result
     * @param array $collectItemData
     *
     * @return array
     */
    protected function addExtraCollectorFields(array $result, array $collectItemData)
    {
        $result[CollectorConfig::COLLECTOR_TOUCH_ID] = $collectItemData[CollectorConfig::COLLECTOR_TOUCH_ID];
        $result[CollectorConfig::COLLECTOR_RESOURCE_ID] = $collectItemData[CollectorConfig::COLLECTOR_RESOURCE_ID];
        $result[CollectorConfig::COLLECTOR_SEARCH_KEY] = $collectItemData[CollectorConfig::COLLECTOR_SEARCH_KEY];

        return $result;
    }

}
