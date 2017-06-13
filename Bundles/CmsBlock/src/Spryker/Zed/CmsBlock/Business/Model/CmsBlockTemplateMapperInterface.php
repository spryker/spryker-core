<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;

interface CmsBlockTemplateMapperInterface
{

    /**
     * @param SpyCmsBlockTemplate $spyCmsBlockTemplate
     *
     * @return CmsBlockTemplateTransfer
     */
    public function convertTemplateEntityToTransfer(SpyCmsBlockTemplate $spyCmsBlockTemplate);

}