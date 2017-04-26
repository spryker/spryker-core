<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\CmsGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsGuiToCmsInterface
{

    /**
     * @param int $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    public function hasPagePlaceholderMapping($idPage, $placeholder);

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return int
     */
    public function createPage(CmsPageTransfer $cmsPageTransfer);

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer|null
     */
    public function findPageGlossaryAttributes($idCmsPage);

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function saveCmsGlossary(CmsGlossaryTransfer $cmsGlossaryTransfer);

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function findCmsPageById($idCmsPage);

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function updatePage(CmsPageTransfer $cmsPageTransfer);

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function activatePage($idCmsPage);

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deactivatePage($idCmsPage);

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function getPageUrlPrefix(CmsPageAttributesTransfer $cmsPageAttributesTransfer);

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer);

    /**
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @return CmsVersionTransfer
     */
    public function publishAndVersion($idCmsPage, $versionName = null);

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return CmsVersionTransfer
     */
    public function rollback($idCmsPage, $version);

    /**
     * @param int $idCmsPage
     *
     * @return bool
     */
    public function revert($idCmsPage);

    /**
     * @param int $idCmsPage
     *
     * @return CmsVersionTransfer
     */
    public function findLatestCmsVersionByIdCmsPage($idCmsPage);

    /**
     * @param int $idCmsPage
     *
     * @return CmsVersionTransfer[]
     */
    public function findAllCmsVersionByIdCmsPage($idCmsPage);

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return CmsVersionTransfer
     */
    public function findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate($cmsTemplateFolderPath);

}
