<?php


namespace Spryker\Zed\CmsBlock\Business;


use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
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
     * - Updates CMS Block
     * - Asserts that chosen template still exists
     *
     * @api
     *
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * Specification
     * - Creates a CMS Block record
     * - Asserts that chosen template still exists
     * - Sets CMS Block ID to transfer
     *
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * Specification
     * - Loops by all template files in passed path
     * - Creates records with templates in DB
     *
     * @api
     *
     * @param string $templatePath
     *
     * @return bool
     */
    public function syncTemplate($templatePath);

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function findGlossaryPlaceholders($idCmsBlock);

    /**
     * Specification
     * - Creates new or updates nested placeholders
     *
     * @api
     *
     * @param CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer);

}