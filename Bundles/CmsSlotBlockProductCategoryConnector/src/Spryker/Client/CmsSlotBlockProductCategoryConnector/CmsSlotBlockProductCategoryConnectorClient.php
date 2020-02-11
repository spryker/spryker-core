<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlotBlockProductCategoryConnector\CmsSlotBlockProductCategoryConnectorFactory getFactory()
 */
class CmsSlotBlockProductCategoryConnectorClient extends AbstractClient implements CmsSlotBlockProductCategoryConnectorClientInterface
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
            ->createProductCategoryCmsSlotBlockConditionResolver()
            ->isSlotBlockConditionApplicable($cmsSlotBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        return $this->getFactory()
            ->createProductCategoryCmsSlotBlockConditionResolver()
            ->isCmsBlockVisibleInSlot($cmsBlockTransfer, $cmsSlotParamsTransfer);
    }
}
