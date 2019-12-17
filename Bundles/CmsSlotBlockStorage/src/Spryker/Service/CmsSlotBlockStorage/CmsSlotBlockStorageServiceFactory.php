<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\CmsSlotBlockStorage;

use Spryker\Service\CmsSlotBlockStorage\KeyBuilder\CmsSlotBlockStorageKeyBuilder;
use Spryker\Service\CmsSlotBlockStorage\KeyBuilder\CmsSlotBlockStorageKeyBuilderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

class CmsSlotBlockStorageServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\CmsSlotBlockStorage\KeyBuilder\CmsSlotBlockStorageKeyBuilderInterface
     */
    public function createCmsSlotBlockStorageKeyBuilder(): CmsSlotBlockStorageKeyBuilderInterface
    {
        return new CmsSlotBlockStorageKeyBuilder();
    }
}
