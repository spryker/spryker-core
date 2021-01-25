<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector;

use Spryker\Client\CmsSlotBlockCmsConnector\Resolver\CmsPageCmsSlotBlockConditionResolver;
use Spryker\Client\CmsSlotBlockCmsConnector\Resolver\CmsPageCmsSlotBlockConditionResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotBlockCmsConnectorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CmsSlotBlockCmsConnector\Resolver\CmsPageCmsSlotBlockConditionResolverInterface
     */
    public function createCmsPageCmsSlotBlockConditionResolver(): CmsPageCmsSlotBlockConditionResolverInterface
    {
        return new CmsPageCmsSlotBlockConditionResolver();
    }
}
