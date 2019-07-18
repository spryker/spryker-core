<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Map;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector;
use Spryker\Zed\CmsCollector\Persistence\Collector\Search\Propel\CmsVersionPageCollectorQuery;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class CmsDataPageMapBuilder implements PageMapInterface
{
    public const TYPE_CMS_PAGE = 'cms_page';
    public const TYPE = 'type';
    public const ID_CMS_PAGE = 'id_cms_page';
    public const NAME = 'name';

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface $cmsFacade
     */
    public function __construct(CmsCollectorToCmsInterface $cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
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
        $cmsVersionDataTransfer = $this->cmsFacade->extractCmsVersionDataTransfer($cmsPageData[CmsVersionPageCollectorQuery::COL_DATA]);
        $localeCmsPageDataTransfer = $this->cmsFacade->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, $localeTransfer);

        $isActive = $cmsPageData[AbstractCmsVersionPageCollector::COL_IS_ACTIVE] && $cmsPageData[AbstractCmsVersionPageCollector::COL_IS_SEARCHABLE];

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore(Store::getInstance()->getStoreName())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CMS_PAGE)
            ->setIsActive($isActive);

        $this->setActiveInDateRange($cmsPageData, $pageMapTransfer);

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::ID_CMS_PAGE, $localeCmsPageDataTransfer->getIdCmsPage())
            ->addSearchResultData($pageMapTransfer, static::NAME, $localeCmsPageDataTransfer->getName())
            ->addSearchResultData($pageMapTransfer, static::TYPE, static::TYPE_CMS_PAGE)
            ->addSearchResultData($pageMapTransfer, CmsVersionPageCollectorQuery::COL_URL, $cmsPageData[CmsVersionPageCollectorQuery::COL_URL])
            ->addFullTextBoosted($pageMapTransfer, $localeCmsPageDataTransfer->getName())
            ->addFullText($pageMapTransfer, $localeCmsPageDataTransfer->getMetaTitle())
            ->addFullText($pageMapTransfer, $localeCmsPageDataTransfer->getMetaKeywords())
            ->addFullText($pageMapTransfer, $localeCmsPageDataTransfer->getMetaDescription())
            ->addFullText($pageMapTransfer, implode(',', $localeCmsPageDataTransfer->getPlaceholders()))
            ->addSuggestionTerms($pageMapTransfer, $localeCmsPageDataTransfer->getName())
            ->addCompletionTerms($pageMapTransfer, $localeCmsPageDataTransfer->getName());

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
}
