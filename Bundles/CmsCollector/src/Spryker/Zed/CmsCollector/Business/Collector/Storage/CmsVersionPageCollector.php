<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface;
use Spryker\Zed\CmsCollector\Dependency\Service\CmsCollectorToUtilEncodingInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\Storage\Propel\CmsVersionPageCollectorQuery;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsVersionPageCollector extends AbstractStoragePropelCollector
{

    /**
     * @var DataExtractorInterface
     */
    protected $dataExtractor;

    /**
     * @param UtilDataReaderServiceInterface $utilDataReaderService
     * @param DataExtractorInterface $dataExtractorDataPage
     */
    public function __construct(UtilDataReaderServiceInterface $utilDataReaderService, DataExtractorInterface $dataExtractorDataPage)
    {
        parent::__construct($utilDataReaderService);

        $this->dataExtractor = $dataExtractorDataPage;
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

        return [
            'url' => $collectItemData[CmsVersionPageCollectorQuery::COL_URL],
            'valid_from' => $cmsPageTransfer->getValidFrom(),
            'valid_to' => $cmsPageTransfer->getValidTo(),
            'is_active' => $cmsPageTransfer->getIsActive(),
            'id' => $cmsPageTransfer->getFkPage(),
            'template' => $cmsVersionDataTransfer->getCmsTemplate()->getTemplatePath(),
            'placeholders' => $this->dataExtractor->extractPlaceholdersByLocale($cmsVersionDataTransfer->getCmsGlossary(), $localeName),
            'name' => $cmsPageAttributeTransfer->getName(),
            'meta_title' => $cmsMetaAttributeTransfer->getMetaTitle(),
            'meta_keywords' => $cmsMetaAttributeTransfer->getMetaKeywords(),
            'meta_description' => $cmsMetaAttributeTransfer->getMetaDescription(),
        ];
    }



    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_PAGE;
    }

}
