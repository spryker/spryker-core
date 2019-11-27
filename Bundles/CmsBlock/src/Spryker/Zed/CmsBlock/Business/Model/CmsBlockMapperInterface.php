<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;

interface CmsBlockMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $spyCmsBlock): CmsBlockTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock
     */
    public function mapCmsBlockTransferToEntity(CmsBlockTransfer $cmsBlockTransfer, SpyCmsBlock $spyCmsBlock): SpyCmsBlock;
}
