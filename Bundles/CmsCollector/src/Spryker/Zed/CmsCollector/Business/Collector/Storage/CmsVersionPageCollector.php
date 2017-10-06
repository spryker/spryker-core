<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Collector\Storage;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\Storage\Propel\CmsVersionPageCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsVersionPageCollector extends AbstractStoragePropelCollector
{

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface $cmsFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        CmsCollectorToCmsInterface $cmsFacade
    ) {
        parent::__construct($utilDataReaderService);

        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $cmsVersionDataTransfer = $this->cmsFacade->extractCmsVersionDataTransfer($collectItemData[CmsVersionPageCollectorQuery::COL_DATA]);
        $localeCmsPageDataTransfer = $this->cmsFacade->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, $this->locale);
        $localeCmsPageDataTransfer
            ->setValidFrom($collectItemData[CmsVersionPageCollectorQuery::COL_VALID_FROM])
            ->setValidTo($collectItemData[CmsVersionPageCollectorQuery::COL_VALID_TO])
            ->setUrl($collectItemData[CmsVersionPageCollectorQuery::COL_URL])
            ->setIsActive($collectItemData[CmsVersionPageCollectorQuery::COL_IS_ACTIVE]);

        return $this->cmsFacade->calculateFlattenedLocaleCmsPageData($localeCmsPageDataTransfer, $this->locale);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_PAGE;
    }

}
