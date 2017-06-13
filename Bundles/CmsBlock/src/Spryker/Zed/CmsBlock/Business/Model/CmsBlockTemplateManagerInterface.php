<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;

interface CmsBlockTemplateManagerInterface
{
    /**
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath);

    /**
     * @param $name
     * @param $path
     *
     * @return CmsBlockTemplateTransfer
     */
    public function createTemplate($name, $path);

    /**
     * @param string $path
     *
     * @throws CmsBlockTemplateNotFoundException
     *
     * @return void
     */
    public function checkTemplateFileExists($path);

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return SpyCmsBlockTemplate
     */
    public function getTemplateById($idCmsBlockTemplate);

}