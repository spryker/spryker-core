<?php


namespace Spryker\Zed\CmsBlock\Business;


interface CmsBlockFacadeInterface
{

    /**
     * Specification:
     * - Returns an CMS Block transfer by id
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById($idCmsBlock);

}