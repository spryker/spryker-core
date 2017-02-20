<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageReader implements CmsPageReaderInterface
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface
     */
    protected $cmsPageUrlBuilder;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageUrlBuilderInterface $cmsPageUrlBuilder
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsPageUrlBuilderInterface $cmsPageUrlBuilder
    ) {

        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageUrlBuilder = $cmsPageUrlBuilder;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function findCmsPageById($idCmsPage)
    {
        $cmsPageEntity = $this->findCmsPageEntity($idCmsPage);

        if ($cmsPageEntity === null) {
            return null;
        }

        $cmsPageTransfer = $this->mapCmsPageTransfer($cmsPageEntity);
        $urlLocaleMap = $this->createUrlLocaleMap($cmsPageEntity);

        foreach ($cmsPageEntity->getSpyCmsPageLocalizedAttributess() as $cmsPageLocalizedAttributesEntity) {

            $url = $this->getLocalizedUrl($urlLocaleMap, $cmsPageLocalizedAttributesEntity);

            $cmsPageAttributesTransfer = $this->mapCmsLocalizedAttributesTransfer(
                $cmsPageLocalizedAttributesEntity,
                $url
            );
            $cmsPageTransfer->addPageAttribute($cmsPageAttributesTransfer);

            $cmsCmsPageMetaAttributes = $this->mapCmsPageMetaAttributes($cmsPageLocalizedAttributesEntity);
            $cmsPageTransfer->addMetaAttribute($cmsCmsPageMetaAttributes);

        }

        return $cmsPageTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return array
     */
    protected function createUrlLocaleMap(SpyCmsPage $cmsPageEntity)
    {
        $urlLocaleMap = [];
        foreach ($cmsPageEntity->getSpyUrls() as $urlEntity) {
            $urlLocaleMap[$urlEntity->getFkLocale()] = $urlEntity->getUrl();
        }
        return $urlLocaleMap;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     * @param string|null $url
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    protected function mapCmsLocalizedAttributesTransfer(
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity,
        $url = null
    ) {
        $localeEntity = $cmsPageLocalizedAttributesEntity->getLocale();
        $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributesTransfer->fromArray($cmsPageLocalizedAttributesEntity->toArray(), true);
        $cmsPageAttributesTransfer->setIdCmsPage($cmsPageLocalizedAttributesEntity->getFkCmsPage());
        $cmsPageAttributesTransfer->setLocaleName($localeEntity->getLocaleName());
        $cmsPageAttributesTransfer->setUrl($url);
        $cmsPageAttributesTransfer->setUrlPrefix(
            $this->cmsPageUrlBuilder->getPageUrlPrefix($cmsPageAttributesTransfer)
        );

        return $cmsPageAttributesTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer
     */
    protected function mapCmsPageMetaAttributes(SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity)
    {
        $localeEntity = $cmsPageLocalizedAttributesEntity->getLocale();
        $cmsCmsPageMetaAttributes = new CmsPageMetaAttributesTransfer();
        $cmsCmsPageMetaAttributes->fromArray($cmsPageLocalizedAttributesEntity->toArray(), true);
        $cmsCmsPageMetaAttributes->setLocaleName($localeEntity->getLocaleName());

        return $cmsCmsPageMetaAttributes;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|null
     */
    protected function findCmsPageEntity($idCmsPage)
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($idCmsPage)
            ->findOne();

        return $cmsPageEntity;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function mapCmsPageTransfer(SpyCmsPage $cmsPageEntity)
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->setTemplateName($cmsPageEntity->getCmsTemplate()->getTemplateName());
        $cmsPageTransfer->setFkPage($cmsPageEntity->getIdCmsPage());
        $cmsPageTransfer->fromArray($cmsPageEntity->toArray(), true);

        return $cmsPageTransfer;
    }

    /**
     * @param array $urlLocaleMap
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     *
     * @return null|string
     */
    protected function getLocalizedUrl(
        array $urlLocaleMap,
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
    ) {
        $url = null;
        if (isset($urlLocaleMap[$cmsPageLocalizedAttributesEntity->getFkLocale()])) {
            $url = $urlLocaleMap[$cmsPageLocalizedAttributesEntity->getFkLocale()];
        }
        return $url;
    }

}
