<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence\Mapper;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;

interface CmsBlockMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $cmsBlockEntity): CmsBlockTransfer;
}
