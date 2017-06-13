<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


interface CmsBlockTemplateManagerInterface
{
    /**
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath);

}