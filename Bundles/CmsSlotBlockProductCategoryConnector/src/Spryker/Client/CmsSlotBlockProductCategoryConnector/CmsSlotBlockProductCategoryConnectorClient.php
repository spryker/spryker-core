<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector;

use Generated\Shared\Transfer\CmsBlockTransfer;
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
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function resolveIsSlotBlockConditionApplicable(CmsBlockTransfer $cmsBlockTransfer): bool
    {
        return $this->getFactory()
            ->createProductCategoryCmsSlotBlockConditionResolver()
            ->resolveIsSlotBlockConditionApplicable($cmsBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function resolveIsCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotData): bool
    {
        return $this->getFactory()
            ->createProductCategoryCmsSlotBlockConditionResolver()
            ->resolveIsCmsBlockVisibleInSlot($cmsBlockTransfer, $cmsSlotData);
    }
}
