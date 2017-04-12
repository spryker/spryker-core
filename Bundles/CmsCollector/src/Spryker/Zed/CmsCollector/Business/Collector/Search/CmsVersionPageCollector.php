<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
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
     * @var \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface
     */
    protected $cmsPageDataPageMapPlugin;

    /**
     * @var CmsCollectorToSearchInterface
     */
    protected $searchFacade;

    /**
     * @param UtilDataReaderServiceInterface $utilDataReaderService
     * @param PageMapInterface $cmsPageDataPageMapPlugin
     * @param CmsCollectorToSearchInterface $searchFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        PageMapInterface $cmsPageDataPageMapPlugin,
        CmsCollectorToSearchInterface $searchFacade
    ) {
        parent::__construct($utilDataReaderService);

        $this->cmsPageDataPageMapPlugin = $cmsPageDataPageMapPlugin;
        $this->searchFacade = $searchFacade;
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
            ->transformPageMapToDocument($this->cmsPageDataPageMapPlugin, $collectItemData, $this->locale);

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
