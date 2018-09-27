<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

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
     * @var \Spryker\Zed\Cms\Business\Page\CmsPageMapperInterface
     */
    protected $cmsPageMapper;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageMapperInterface $cmsPageMapper
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsPageMapperInterface $cmsPageMapper
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageMapper = $cmsPageMapper;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function findCmsPageById(int $idCmsPage): ?CmsPageTransfer
    {
        $cmsPageEntity = $this->findCmsPageEntity($idCmsPage);

        if ($cmsPageEntity === null) {
            return null;
        }

        $cmsPageMapper = $this->cmsPageMapper;
        $cmsPageTransfer = $cmsPageMapper->mapCmsPageTransfer($cmsPageEntity);
        $urlLocaleMap = $cmsPageMapper->mapCmsPageUrlLocale($cmsPageEntity);

        foreach ($cmsPageEntity->getSpyCmsPageLocalizedAttributess() as $cmsPageLocalizedAttributesEntity) {
            $url = $this->getLocalizedUrl($urlLocaleMap, $cmsPageLocalizedAttributesEntity);

            $cmsPageAttributesTransfer = $cmsPageMapper->mapCmsLocalizedAttributesTransfer(
                $cmsPageLocalizedAttributesEntity,
                $url
            );
            $cmsPageTransfer->addPageAttribute($cmsPageAttributesTransfer);

            $cmsCmsPageMetaAttributes = $cmsPageMapper->mapCmsPageMetaAttributes($cmsPageLocalizedAttributesEntity);
            $cmsPageTransfer->addMetaAttribute($cmsCmsPageMetaAttributes);
        }

        return $cmsPageTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|null
     */
    protected function findCmsPageEntity(int $idCmsPage): ?SpyCmsPage
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($idCmsPage)
            ->findOne();

        return $cmsPageEntity;
    }

    /**
     * @param array $urlLocaleMap
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     *
     * @return string|null
     */
    protected function getLocalizedUrl(
        array $urlLocaleMap,
        SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
    ): ?string {
        $url = null;
        if (isset($urlLocaleMap[$cmsPageLocalizedAttributesEntity->getFkLocale()])) {
            $url = $urlLocaleMap[$cmsPageLocalizedAttributesEntity->getFkLocale()];
        }
        return $url;
    }
}
