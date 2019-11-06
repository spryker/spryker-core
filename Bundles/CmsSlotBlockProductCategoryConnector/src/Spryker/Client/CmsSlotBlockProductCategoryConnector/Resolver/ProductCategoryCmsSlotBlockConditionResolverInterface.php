<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver;

interface ProductCategoryCmsSlotBlockConditionResolverInterface
{
    /**
     * @param array $conditionData
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function resolveIsCmsBlockVisibleInSlot(array $conditionData, array $cmsSlotData): bool;
}
