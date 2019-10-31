<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\CmsSlotBlock;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlotBlock\CmsSlotBlockFactory getFactory()
 */
class CmsSlotBlockClient extends AbstractClient implements CmsSlotBlockClientInterface
{
    /**
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
        return $this->getFactory()->createCmsSlotBlockVisibilityResolver()->isCmsBlockVisibleInSlot(
            $cmsBlockTransfer,
            $conditions,
            $cmsSlotData
        );
    }
}