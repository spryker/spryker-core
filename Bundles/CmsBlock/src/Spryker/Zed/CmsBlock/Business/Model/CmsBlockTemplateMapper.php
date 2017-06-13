<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;

class CmsBlockTemplateMapper implements CmsBlockTemplateMapperInterface
{

    /**
     * @param SpyCmsBlockTemplate $spyCmsBlockTemplate
     *
     * @return CmsBlockTemplateTransfer
     */
    public function convertTemplateEntityToTransfer(SpyCmsBlockTemplate $spyCmsBlockTemplate)
    {
        $cmsBlockTemplateTransfer = new CmsBlockTemplateTransfer();
        $cmsBlockTemplateTransfer->fromArray($spyCmsBlockTemplate->toArray(), true);

        return $cmsBlockTemplateTransfer;
    }


}