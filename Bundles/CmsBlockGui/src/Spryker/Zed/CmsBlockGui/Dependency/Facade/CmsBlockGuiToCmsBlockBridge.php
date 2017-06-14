<?php


namespace Spryker\Zed\CmsBlockGui\Dependency\Facade;


use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;

class CmsBlockGuiToCmsBlockBridge implements CmsBlockGuiToCmsBlockInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected $cmsBlockFacade;

    /**
     * @param \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface $cmsBlockFacade
     */
    public function __construct($cmsBlockFacade)
    {
        $this->cmsBlockFacade = $cmsBlockFacade;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockTransfer|null
     */
    public function findCmsBlockId($idCmsBlock)
    {
        return $this->cmsBlockFacade->findCmsBlockById($idCmsBlock);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock)
    {
        $this->cmsBlockFacade->activateById($idCmsBlock);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     *
     */
    public function deactivateById($idCmsBlock)
    {
        $this->cmsBlockFacade->deactivateById($idCmsBlock);
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        return $this->cmsBlockFacade->updateCmsBlock($cmsBlockTransfer);
    }

    /**
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath)
    {
        return $this->cmsBlockFacade->syncTemplate($templatePath);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function findGlossaryPlaceholders($idCmsBlock)
    {
        return $this->cmsBlockFacade->findGlossaryPlaceholders($idCmsBlock);
    }

    /**
     * @param CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        return $this->cmsBlockFacade->saveGlossary($cmsBlockGlossaryTransfer);
    }
}