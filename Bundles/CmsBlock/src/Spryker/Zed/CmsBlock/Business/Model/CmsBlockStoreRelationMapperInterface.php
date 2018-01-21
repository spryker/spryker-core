<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;

interface CmsBlockStoreRelationMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlock
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStoreRelationToTransfer(SpyCmsBlock $cmsBlock);
}
