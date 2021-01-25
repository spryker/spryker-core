<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorFactory getFactory()
 */
class CmsSlotBlockCategoryConnectorClient extends AbstractClient implements CmsSlotBlockCategoryConnectorClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return bool
     */
    public function isSlotBlockConditionApplicable(CmsSlotBlockTransfer $cmsSlotBlockTransfer): bool
    {
        return $this->getFactory()
            ->createCategoryCmsSlotBlockConditionResolver()
            ->isSlotBlockConditionApplicable($cmsSlotBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        return $this->getFactory()
            ->createCategoryCmsSlotBlockConditionResolver()
            ->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);
    }
}
