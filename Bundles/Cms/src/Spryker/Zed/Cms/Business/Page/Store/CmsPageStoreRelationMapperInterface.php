<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page\Store;

use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;

interface CmsPageStoreRelationMapperInterface
{
    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapStoreRelationToTransfer(SpyCmsPage $cmsPageEntity): StoreRelationTransfer;
}
