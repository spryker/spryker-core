<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Resolver;

interface CategoryCmsSlotBlockConditionResolverInterface
{
    /**
     * @param array $conditionData
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function getIsCmsBlockVisibleInSlot(array $conditionData, array $cmsSlotData): bool;
}
