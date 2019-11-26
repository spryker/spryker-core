<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector\Resolver;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Shared\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorConfig;

class CmsPageCmsSlotBlockConditionResolver implements CmsPageCmsSlotBlockConditionResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return bool
     */
    public function isSlotBlockConditionApplicable(CmsSlotBlockTransfer $cmsSlotBlockTransfer): bool
    {
        return $cmsSlotBlockTransfer->getConditions()
            ->offsetExists(CmsSlotBlockCmsConnectorConfig::CONDITION_KEY);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        /** @var \Generated\Shared\Transfer\CmsSlotBlockConditionTransfer $cmsSlotBlockConditionTransfer */
        $cmsSlotBlockConditionTransfer = $cmsSlotBlockTransfer->getConditions()
            ->offsetGet(CmsSlotBlockCmsConnectorConfig::CONDITION_KEY);

        if ($cmsSlotBlockConditionTransfer->getAll()) {
            return true;
        }

        return $cmsSlotParamsTransfer->getIdCmsPage()
            && in_array($cmsSlotParamsTransfer->getIdCmsPage(), $cmsSlotBlockConditionTransfer->getCmsPageIds());
    }
}
