<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface;
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
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageMapperInterface $cmsPageMapper
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsPageMapperInterface $cmsPageMapper,
        CmsToLocaleFacadeInterface $localeFacade
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsPageMapper = $cmsPageMapper;
        $this->localeFacade = $localeFacade;
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

        $cmsPageTransfer = $this->cmsPageMapper->mapCmsPageTransfer($cmsPageEntity);

        return $this->hydrateCmsPageWithLocalizedAttributes($cmsPageTransfer, $cmsPageEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function hydrateCmsPageWithLocalizedAttributes(CmsPageTransfer $cmsPageTransfer, SpyCmsPage $cmsPageEntity): CmsPageTransfer
    {
        $availableLocales = $this->localeFacade->getAvailableLocales();
        $urlLocaleMap = $this->cmsPageMapper->mapCmsPageUrlLocale($cmsPageEntity);
        $localizedAttributesIdEntityMap = $this->createKeyMappingByLocalizedAttributes($cmsPageEntity);

        foreach ($availableLocales as $idLocale => $localeName) {
            $cmsPageLocalizedAttributesEntity = $this->getLocalizedAttributesByLocale(
                $localizedAttributesIdEntityMap,
                $idLocale
            );

            $url = $this->getLocalizedUrl($urlLocaleMap, $cmsPageLocalizedAttributesEntity);

            $cmsPageAttributesTransfer = $this->cmsPageMapper->mapCmsLocalizedAttributesTransfer(
                $cmsPageLocalizedAttributesEntity,
                $url
            );
            $cmsPageTransfer->addPageAttribute($cmsPageAttributesTransfer);

            $cmsCmsPageMetaAttributes = $this->cmsPageMapper->mapCmsPageMetaAttributes($cmsPageLocalizedAttributesEntity);
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

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes[]
     */
    protected function createKeyMappingByLocalizedAttributes(SpyCmsPage $cmsPageEntity): array
    {
        $localizedAttributesMap = [];
        foreach ($cmsPageEntity->getSpyCmsPageLocalizedAttributess() as $cmsPageLocalizedAttributesEntity) {
            $localizedAttributesMap[$cmsPageLocalizedAttributesEntity->getFkLocale()] = $cmsPageLocalizedAttributesEntity;
        }

        return $localizedAttributesMap;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes[] $localizedAttributesIdEntityMap
     * @param int $idLocale
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes
     */
    protected function getLocalizedAttributesByLocale(array $localizedAttributesIdEntityMap, int $idLocale): SpyCmsPageLocalizedAttributes
    {
        if (isset($localizedAttributesIdEntityMap[$idLocale])) {
            return $localizedAttributesIdEntityMap[$idLocale];
        }

        return (new SpyCmsPageLocalizedAttributes())->setFkLocale($idLocale);
    }
}
