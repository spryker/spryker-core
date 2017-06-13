<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


interface CmsBlockGlossaryKeyGeneratorInterface
{
    /**
     * @param int $idCmsBlock
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName($idCmsBlock, $templateName, $placeholder, $autoIncrement = true);

}