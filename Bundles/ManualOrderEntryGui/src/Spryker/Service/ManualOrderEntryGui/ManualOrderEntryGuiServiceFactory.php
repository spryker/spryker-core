<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ManualOrderEntryGui;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\ManualOrderEntryGui\FloatRounder\FloatRounder;
use Spryker\Service\ManualOrderEntryGui\FloatRounder\FloatRounderInterface;

/**
 * @method \Spryker\Service\ManualOrderEntryGui\ManualOrderEntryGuiConfig getConfig()
 */
class ManualOrderEntryGuiServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\ManualOrderEntryGui\FloatRounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
