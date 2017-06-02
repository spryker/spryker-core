<?php


namespace Spryker\Zed\CmsGui\Dependency\Facade;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface;

class CmsGuiToCmsBlockBridge implements CmsGuiToCmsBlockInterface
{
    /**
     * @var CmsBlockFacadeInterface
     */
    protected $cmsBlockFacade;

    public function __construct($cmsBlockFacade)
    {
        $this->cmsBlockFacade = $cmsBlockFacade;
    }

    /**
     * @param $idCmsBlock
     *
     * @return CmsBlockTransfer|null
     */
    public function findCmsBlockId($idCmsBlock)
    {
        return $this->cmsBlockFacade->findCmsBlockById($idCmsBlock);
    }

}