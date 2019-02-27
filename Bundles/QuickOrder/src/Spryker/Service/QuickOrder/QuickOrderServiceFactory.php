<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\QuickOrder;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\QuickOrder\Rounder\FloatRounder;
use Spryker\Service\QuickOrder\Rounder\FloatRounderInterface;

/**
 * @method \Spryker\Service\QuickOrder\QuickOrderConfig getConfig()
 */
class QuickOrderServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\QuickOrder\Rounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
