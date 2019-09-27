<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;

class CmsGuiToCmsBridge implements CmsGuiToCmsInterface
{
    /**
     * @var \Spryker\Zed\Cms\Business\CmsFacadeInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Zed\Cms\Business\CmsFacadeInterface $cmsFacade
     */
    public function __construct($cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder)
    {
        return $this->cmsFacade->hasPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return int
     */
    public function createPage(CmsPageTransfer $cmsPageTransfer)
    {
        return $this->cmsFacade->createPage($cmsPageTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer|null
     */
    public function findPageGlossaryAttributes($idCmsPage)
    {
        return $this->cmsFacade->findPageGlossaryAttributes($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function saveCmsGlossary(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        return $this->cmsFacade->saveCmsGlossary($cmsGlossaryTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function findCmsPageById($idCmsPage)
    {
        return $this->cmsFacade->findCmsPageById($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function updatePage(CmsPageTransfer $cmsPageTransfer)
    {
        return $this->cmsFacade->updatePage($cmsPageTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function activatePage($idCmsPage)
    {
        $this->cmsFacade->activatePage($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deactivatePage($idCmsPage)
    {
        $this->cmsFacade->deactivatePage($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function getPageUrlPrefix(CmsPageAttributesTransfer $cmsPageAttributesTransfer)
    {
        return $this->cmsFacade->getPageUrlPrefix($cmsPageAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer)
    {
        return $this->cmsFacade->buildPageUrl($cmsPageAttributesTransfer);
    }

    /**
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function publishWithVersion($idCmsPage, $versionName = null)
    {
        return $this->cmsFacade->publishWithVersion($idCmsPage, $versionName);
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function rollback($idCmsPage, $version)
    {
        return $this->cmsFacade->rollback($idCmsPage, $version);
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function revert($idCmsPage)
    {
        $this->cmsFacade->revert($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    public function findLatestCmsVersionByIdCmsPage($idCmsPage)
    {
        return $this->cmsFacade->findLatestCmsVersionByIdCmsPage($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer[]
     */
    public function findAllCmsVersionByIdCmsPage($idCmsPage)
    {
        return $this->cmsFacade->findAllCmsVersionByIdCmsPage($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer|null
     */
    public function findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version)
    {
        return $this->cmsFacade->findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);
    }

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath)
    {
        return $this->cmsFacade->syncTemplate($cmsTemplateFolderPath);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function getCmsVersionData(int $idCmsPage): CmsVersionDataTransfer
    {
        return $this->cmsFacade->getCmsVersionData($idCmsPage);
    }
}
