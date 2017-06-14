<?php


namespace Spryker\Zed\CmsBlockGui\Dependency\Facade;


use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockGuiToCmsBlockInterface
{

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockTransfer|null
     */
    public function findCmsBlockId($idCmsBlock);

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock);

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath);

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function findGlossaryPlaceholders($idCmsBlock);

    /**
     * @param CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer);

}