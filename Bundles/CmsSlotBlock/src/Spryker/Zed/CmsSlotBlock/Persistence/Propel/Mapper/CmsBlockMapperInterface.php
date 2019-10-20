<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Persistence\Propel\Mapper;

use Propel\Runtime\Collection\Collection;

interface CmsBlockMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection $cmsBlockEntities
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function mapCmsBlockEntitiesToTransfers(Collection $cmsBlockEntities): array;
}
