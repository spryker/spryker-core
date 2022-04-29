<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Map;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface;
use Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToStoreFacadeInterface;
use Spryker\Zed\CmsCollector\Persistence\Collector\AbstractCmsVersionPageCollector;
use Spryker\Zed\CmsCollector\Persistence\Collector\Search\Propel\CmsVersionPageCollectorQuery;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

/**
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class CmsDataPageMapBuilder implements PageMapInterface
{
    /**
     * @var string
     */
    public const TYPE_CMS_PAGE = 'cms_page';

    /**
     * @var string
     */
    public const TYPE = 'type';

    /**
     * @var string
     */
    public const ID_CMS_PAGE = 'id_cms_page';

    /**
     * @var string
     */
    public const NAME = 'name';

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToCmsInterface $cmsFacade
     * @param \Spryker\Zed\CmsCollector\Dependency\Facade\CmsCollectorToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        CmsCollectorToCmsInterface $cmsFacade,
        CmsCollectorToStoreFacadeInterface $storeFacade
    ) {
        $this->cmsFacade = $cmsFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $data, LocaleTransfer $localeTransfer)
    {
        $cmsVersionDataTransfer = $this->cmsFacade->extractCmsVersionDataTransfer($data[CmsVersionPageCollectorQuery::COL_DATA]);
        $localeCmsPageDataTransfer = $this->cmsFacade->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, $localeTransfer);

        $isActive = $data[AbstractCmsVersionPageCollector::COL_IS_ACTIVE] && $data[AbstractCmsVersionPageCollector::COL_IS_SEARCHABLE];

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($this->storeFacade->getCurrentStore()->getNameOrFail())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CMS_PAGE)
            ->setIsActive($isActive);

        $this->setActiveInDateRange($data, $pageMapTransfer);

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::ID_CMS_PAGE, $localeCmsPageDataTransfer->getIdCmsPage())
            ->addSearchResultData($pageMapTransfer, static::NAME, $localeCmsPageDataTransfer->getName())
            ->addSearchResultData($pageMapTransfer, static::TYPE, static::TYPE_CMS_PAGE)
            ->addSearchResultData($pageMapTransfer, CmsVersionPageCollectorQuery::COL_URL, $data[CmsVersionPageCollectorQuery::COL_URL])
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
                (new DateTime($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_FROM]))->format('Y-m-d'),
            );
        }

        if ($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_TO]) {
            $pageMapTransfer->setActiveTo(
                (new DateTime($cmsPageData[AbstractCmsVersionPageCollector::COL_VALID_TO]))->format('Y-m-d'),
            );
        }
    }
}
