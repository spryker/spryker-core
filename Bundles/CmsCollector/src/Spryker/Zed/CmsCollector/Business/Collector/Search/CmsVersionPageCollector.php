<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Collector\Search;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;

use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchInterface;
use Spryker\Zed\Collector\Business\Collector\Search\AbstractSearchPropelCollector;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

class CmsVersionPageCollector extends AbstractSearchPropelCollector
{

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchInterface
     */
    protected $searchFacade;

    /**
     * @var \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface
     */
    protected $cmsDataPageMapBuilder;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $cmsDataPageMapBuilder
     * @param \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToSearchInterface $searchContentWidgetFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        PageMapInterface $cmsDataPageMapBuilder,
        CmsCollectorToSearchInterface $searchContentWidgetFacade
    ) {
        parent::__construct($utilDataReaderService);

        $this->cmsDataPageMapBuilder = $cmsDataPageMapBuilder;
        $this->searchFacade = $searchContentWidgetFacade;
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_PAGE;
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
            ->transformPageMapToDocument($this->cmsDataPageMapBuilder, $collectItemData, $this->locale);

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
