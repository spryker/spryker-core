<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;

class CmsBlockMapper implements CmsBlockMapperInterface
{
    /**
     * @param SpyCmsBlock $spyCmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $spyCmsBlock)
    {
        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->fromArray($spyCmsBlock->toArray());

        return $cmsBlockTransfer;
    }

}