<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cms\Business\Block;

use Generated\Shared\Transfer\CmsBlockTransfer;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlock;

interface BlockManagerInterface
{

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlock);

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockTransfer $cmsBlock);

    /**
     * @param SpyCmsBlock $block
     *
     * @return CmsBlockTransfer
     */
    public function convertBlockEntityToTransfer(SpyCmsBlock $block);

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlock);

}
