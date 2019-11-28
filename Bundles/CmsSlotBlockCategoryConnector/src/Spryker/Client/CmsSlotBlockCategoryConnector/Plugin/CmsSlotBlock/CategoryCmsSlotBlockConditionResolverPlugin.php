<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Plugin\CmsSlotBlock;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorClientInterface getClient()
 * @method \Spryker\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorFactory getFactory()
 */
class CategoryCmsSlotBlockConditionResolverPlugin extends AbstractPlugin implements CmsSlotBlockVisibilityResolverPluginInterface
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
    public function isApplicable(CmsSlotBlockTransfer $cmsSlotBlockTransfer): bool
    {
        return $this->getClient()->isSlotBlockConditionApplicable($cmsSlotBlockTransfer);
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
        return $this->getClient()->isCmsBlockVisibleInSlot($cmsSlotBlockTransfer, $cmsSlotParamsTransfer);
    }
}
