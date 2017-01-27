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
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageReader implements CmsPageReaderInterface
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     *
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function getCmsPageById($idCmsPage)
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($idCmsPage)
            ->findOne();

        if ($cmsPageEntity == null) {
            throw new MissingPageException(
                sprintf(
                    'Cms page with id "%d" not found.',
                    $idCmsPage
                )
            );
        }

        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->setFkPage($idCmsPage);
        $cmsPageTransfer->fromArray($cmsPageEntity->toArray(), true);

        $urlLocaleMap = $this->createUrlLocaleMap($cmsPageEntity);

        foreach ($cmsPageEntity->getSpyCmsPageLocalizedAttributess() as $cmsPageLocalizedAttributesEntity) {
            $cmsPageLocalizedAttributesArray = $cmsPageLocalizedAttributesEntity->toArray();

            $localeEntity = $cmsPageLocalizedAttributesEntity->getLocale();
            $cmsPageAttributesTransfer = new CmsPageAttributesTransfer();
            $cmsPageAttributesTransfer->fromArray($cmsPageLocalizedAttributesArray, true);
            $cmsPageAttributesTransfer->setIdCmsPage($idCmsPage);
            $cmsPageAttributesTransfer->setLocaleName($localeEntity->getLocaleName());
            $cmsPageAttributesTransfer->setUrl($urlLocaleMap[$cmsPageLocalizedAttributesEntity->getFkLocale()]);
            $cmsPageTransfer->addPageAttribute($cmsPageAttributesTransfer);

            $cmsCmsPageMetaAttributes = new CmsPageMetaAttributesTransfer();
            $cmsCmsPageMetaAttributes->fromArray($cmsPageLocalizedAttributesArray, true);
            $cmsCmsPageMetaAttributes->setLocaleName($localeEntity->getLocaleName());
            $cmsPageTransfer->addMetaAttribute($cmsCmsPageMetaAttributes);

        }

        return $cmsPageTransfer;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
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

}
