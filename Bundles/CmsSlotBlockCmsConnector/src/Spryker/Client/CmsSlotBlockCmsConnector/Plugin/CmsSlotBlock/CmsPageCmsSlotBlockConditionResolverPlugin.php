<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector\Plugin\CmsSlotBlock;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorClientInterface getClient()
 * @method \Spryker\Client\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorFactory getFactory()
 */
class CmsPageCmsSlotBlockConditionResolverPlugin extends AbstractPlugin implements CmsSlotBlockVisibilityResolverPluginInterface
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
    public function isApplicable(CmsBlockTransfer $cmsBlockTransfer): bool
    {
        return $this->getClient()->isSlotBlockConditionApplicable($cmsBlockTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotParams
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotParams): bool
    {
        return $this->getClient()->isCmsBlockVisibleInSlot($cmsBlockTransfer, $cmsSlotParams);
    }
}
