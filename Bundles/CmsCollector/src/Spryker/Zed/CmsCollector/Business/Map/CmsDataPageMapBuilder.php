<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Map;

use DateTime;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector;
use Spryker\Zed\CmsCollector\Persistence\Collector\Search\Propel\CmsVersionPageCollectorQuery;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class CmsDataPageMapBuilder implements PageMapInterface
{

    const TYPE_CMS_PAGE = 'cms_page';
    const TYPE = 'type';
    const ID_CMS_PAGE = 'id_cms_page';
    const NAME = 'name';

    /**
     * @var \Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface
     */
    protected $dataExtractor;

    /**
     * @param \Spryker\Zed\CmsCollector\Business\Extractor\DataExtractorInterface $dataExtractor
     */
    public function __construct(DataExtractorInterface $dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $cmsPageData, LocaleTransfer $localeTransfer)
    {
        $cmsVersionDataTransfer = $this->dataExtractor->extractCmsVersionDataTransfer($cmsPageData[CmsVersionPageCollectorQuery::COL_DATA]);
        $localeName = $localeTransfer->getLocaleName();
        $cmsPageTransfer = $cmsVersionDataTransfer->getCmsPage();
        $cmsPageAttributeTransfer = $this->dataExtractor->extractPageAttributeByLocale($cmsPageTransfer, $localeName);
        $cmsMetaAttributeTransfer = $this->dataExtractor->extractMetaAttributeByLocales($cmsPageTransfer, $localeName);

        $isActive = $cmsPageData[AbstractCmsVersionPageCollector::COL_IS_ACTIVE] && $cmsPageData[AbstractCmsVersionPageCollector::COL_IS_SEARCHABLE];

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore(Store::getInstance()->getStoreName())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CMS_PAGE)
            ->setIsActive($isActive);

        $this->setActiveInDateRange($cmsPageData, $pageMapTransfer);

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::ID_CMS_PAGE, $cmsPageTransfer->getFkPage())
            ->addSearchResultData($pageMapTransfer, static::NAME, $cmsPageAttributeTransfer->getName())
            ->addSearchResultData($pageMapTransfer, static::TYPE, static::TYPE_CMS_PAGE)
            ->addSearchResultData($pageMapTransfer, CmsVersionPageCollectorQuery::COL_URL, $cmsPageData[CmsVersionPageCollectorQuery::COL_URL])
            ->addFullTextBoosted($pageMapTransfer, $cmsPageAttributeTransfer->getName())
            ->addFullText($pageMapTransfer, $cmsMetaAttributeTransfer->getMetaTitle())
            ->addFullText($pageMapTransfer, $cmsMetaAttributeTransfer->getMetaKeywords())
            ->addFullText($pageMapTransfer, $cmsMetaAttributeTransfer->getMetaDescription())
            ->addFullText($pageMapTransfer, $this->extractContents($cmsVersionDataTransfer->getCmsGlossary(), $localeName))
            ->addSuggestionTerms($pageMapTransfer, $cmsPageAttributeTransfer->getName())
            ->addCompletionTerms($pageMapTransfer, $cmsPageAttributeTransfer->getName());

        return $pageMapTransfer;
    }

    /**
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     *
     * @return void
     */
    protected function setActiveInDateRange(array $cmsPageData, PageMapTransfer $pageMapTransfer)
    {
        if ($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_FROM]) {
            $pageMapTransfer->setActiveFrom(
                (new DateTime($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_FROM]))->format('Y-m-d')
            );
        }

        if ($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_TO]) {
            $pageMapTransfer->setActiveTo(
                (new DateTime($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_TO]))->format('Y-m-d')
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string $localeName
     *
     * @return string
     */
    public function extractContents(CmsGlossaryTransfer $cmsGlossaryTransfer, $localeName)
    {
        $placeholders = $this->dataExtractor->extractPlaceholdersByLocale($cmsGlossaryTransfer, $localeName);

        return implode(',', $placeholders);
    }

}
