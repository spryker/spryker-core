<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Dependency\Facade;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Zed\Cms\Business\CmsFacadeInterface;

class CmsGuiToCmsBridge implements CmsGuiToCmsInterface
{

    /**
     * @var CmsFacadeInterface
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
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     *
     */
    public function getPageGlossaryAttributes($idCmsPage)
    {
        return $this->cmsFacade->getPageGlossaryAttributes($idCmsPage);
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
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function getCmsPageById($idCmsPage)
    {
        return $this->cmsFacade->getCmsPageById($idCmsPage);
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

}
