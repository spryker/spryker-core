<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Dependency\Facade;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;

class CmsPageDataImportToCmsFacadeBridge implements CmsPageDataImportToCmsFacadeInterface
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
    public function hasPagePlaceholderMapping(int $idPage, string $placeholder): bool
    {
        return $this->cmsFacade->hasPagePlaceholderMapping($idPage, $placeholder);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return int
     */
    public function createPage(CmsPageTransfer $cmsPageTransfer): int
    {
        return $this->cmsFacade->createPage($cmsPageTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer|null
     */
    public function findPageGlossaryAttributes(int $idCmsPage): ?CmsGlossaryTransfer
    {
        return $this->cmsFacade->findPageGlossaryAttributes($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function saveCmsGlossary(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->cmsFacade->saveCmsGlossary($cmsGlossaryTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    public function findCmsPageById(int $idCmsPage): ?CmsPageTransfer
    {
        return $this->cmsFacade->findCmsPageById($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function updatePage(CmsPageTransfer $cmsPageTransfer): CmsPageTransfer
    {
        return $this->cmsFacade->updatePage($cmsPageTransfer);
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function activatePage(int $idCmsPage): void
    {
        $this->cmsFacade->activatePage($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deactivatePage(int $idCmsPage): void
    {
        $this->cmsFacade->deactivatePage($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function getPageUrlPrefix(CmsPageAttributesTransfer $cmsPageAttributesTransfer): string
    {
        return $this->cmsFacade->getPageUrlPrefix($cmsPageAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageAttributesTransfer $cmsPageAttributesTransfer
     *
     * @return string
     */
    public function buildPageUrl(CmsPageAttributesTransfer $cmsPageAttributesTransfer): string
    {
        return $this->cmsFacade->buildPageUrl($cmsPageAttributesTransfer);
    }

    /**
     * @param int $idCmsPage
     * @param string|null $versionName
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function publishWithVersion(int $idCmsPage, ?string $versionName = null): CmsVersionTransfer
    {
        return $this->cmsFacade->publishWithVersion($idCmsPage, $versionName);
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function rollback(int $idCmsPage, int $version): CmsVersionTransfer
    {
        return $this->cmsFacade->rollback($idCmsPage, $version);
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function revert(int $idCmsPage): void
    {
        $this->cmsFacade->revert($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function findLatestCmsVersionByIdCmsPage(int $idCmsPage): CmsVersionTransfer
    {
        return $this->cmsFacade->findLatestCmsVersionByIdCmsPage($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer[]
     */
    public function findAllCmsVersionByIdCmsPage(int $idCmsPage): array
    {
        return $this->cmsFacade->findAllCmsVersionByIdCmsPage($idCmsPage);
    }

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function findCmsVersionByIdCmsPageAndVersion(int $idCmsPage, int $version): CmsVersionTransfer
    {
        return $this->cmsFacade->findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);
    }

    /**
     * @param string $cmsTemplateFolderPath
     *
     * @return bool
     */
    public function syncTemplate(string $cmsTemplateFolderPath): bool
    {
        return $this->cmsFacade->syncTemplate($cmsTemplateFolderPath);
    }
}
