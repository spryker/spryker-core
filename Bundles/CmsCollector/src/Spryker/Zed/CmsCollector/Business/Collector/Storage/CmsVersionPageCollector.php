<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Collector\Storage;

use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\Storage\Propel\CmsVersionPageCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsVersionPageCollector extends AbstractStoragePropelCollector
{

    /**
     * @var \Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface
     */
    protected $dataExtractor;

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface $dataExtractorDataPage
     * @param \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface $cmsFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        DataExtractorInterface $dataExtractorDataPage,
        CmsCollectorToCmsInterface $cmsFacade
    ) {
        parent::__construct($utilDataReaderService);

        $this->dataExtractor = $dataExtractorDataPage;
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
        $cmsVersionDataTransfer = $this->dataExtractor->extractCmsVersionDataTransfer($collectItemData[CmsVersionPageCollectorQuery::COL_DATA]);
        $localeName = $this->locale->getLocaleName();
        $cmsPageTransfer = $cmsVersionDataTransfer->getCmsPage();
        $cmsPageAttributeTransfer = $this->dataExtractor->extractPageAttributeByLocale($cmsPageTransfer, $localeName);
        $cmsMetaAttributeTransfer = $this->dataExtractor->extractMetaAttributeByLocales($cmsPageTransfer, $localeName);

        $placeHolders = $this->dataExtractor->extractPlaceholdersByLocale($cmsVersionDataTransfer->getCmsGlossary(), $localeName);

        $baseCollectedCmsPageData = [
            'url' => $collectItemData[CmsVersionPageCollectorQuery::COL_URL],
            'valid_from' => $collectItemData[CmsVersionPageCollectorQuery::COL_VALID_FROM],
            'valid_to' => $collectItemData[CmsVersionPageCollectorQuery::COL_VALID_TO],
            'is_active' => $collectItemData[CmsVersionPageCollectorQuery::COL_IS_ACTIVE],
            'id' => $cmsPageTransfer->getFkPage(),
            'template' => $cmsVersionDataTransfer->getCmsTemplate()->getTemplatePath(),
            'placeholders' => $placeHolders,
            'name' => $cmsPageAttributeTransfer->getName(),
            'meta_title' => $cmsMetaAttributeTransfer->getMetaTitle(),
            'meta_keywords' => $cmsMetaAttributeTransfer->getMetaKeywords(),
            'meta_description' => $cmsMetaAttributeTransfer->getMetaDescription(),
        ];

        return $this->cmsFacade->expandCmsPageData($baseCollectedCmsPageData, $this->locale);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_PAGE;
    }

}
