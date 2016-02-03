<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Business\Block;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsBlock;

interface BlockManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsBlock $blockEntity
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function convertBlockEntityToTransfer(SpyCmsBlock $blockEntity);

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockActiveWithKeyChange(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    public function touchBlockDelete(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param int $idCategoryNode
     *
     * @return bool
     */
    public function hasBlockCategoryNodeMapping($idCategoryNode);

    /**
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode);

}
