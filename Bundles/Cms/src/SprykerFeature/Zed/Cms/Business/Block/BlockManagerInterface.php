<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Block;

use Generated\Shared\Cms\CmsBlockInterface;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlock;

interface BlockManagerInterface
{
    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockInterface $cmsBlockTransfer);

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockInterface $cmsBlockTransfer);

    /**
     * @param SpyCmsBlock $blockEntity
     *
     * @return CmsBlockTransfer
     */
    public function convertBlockEntityToTransfer(SpyCmsBlock $blockEntity);

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     */
    public function touchBlockActive(CmsBlockInterface $cmsBlockTransfer);

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     */
    public function touchBlockDelete(CmsBlockInterface $cmsBlockTransfer);
}
