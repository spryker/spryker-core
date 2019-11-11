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
 * @method \Spryker\Client\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorFactory getFactory()
 */
class CmsPageCmsSlotBlockConditionResolverPlugin extends AbstractPlugin implements CmsSlotBlockVisibilityResolverPluginInterface
{
    protected const CONDITION_KEY = 'cmsPage';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $conditions
     *
     * @return bool
     */
    public function isApplicable(array $conditions): bool
    {
        return isset($conditions[static::CONDITION_KEY]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $conditions
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsBlockTransfer $cmsBlockTransfer,
        array $conditions,
        array $cmsSlotData
    ): bool {
        return $this->getFactory()
            ->createCmsPageCmsSlotBlockConditionResolver()
            ->getIsCmsBlockVisibleInSlot($conditions[static::CONDITION_KEY], $cmsSlotData);
    }
}
