<?php


namespace Spryker\Zed\CmsBlockGui\Communication\Plugin;


interface CmsBlockViewPluginInterface
{

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getRenderedList($idCmsBlock, $idLocale);

    /**
     * @return string
     */
    public function getName();

}