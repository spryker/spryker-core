<?php


namespace Spryker\Zed\CmsBlockGui\Communication\Plugin;


interface CmsBlockViewPluginInterface
{

    /**
     * Specification
     * - get a list of rendered subject statements
     *
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return string[]
     */
    public function getRenderedList($idCmsBlock, $idLocale);

    /**
     * Specification
     * - get plugin subject name
     *
     * @api
     *
     * @return string
     */
    public function getName();

}