<?php


namespace Spryker\Zed\CmsBlock\Business;


use Generated\Shared\Transfer\CmsBlockTransfer;

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

    /**
     * Specification:
     * - Changes status of the block to Active
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock);

    /**
     * Specification:
     * - Changes status of the block to Inactive
     *
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock);

    /**
     * Specification
     * - Updates related CmsBlock and its glossary
     *
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

}