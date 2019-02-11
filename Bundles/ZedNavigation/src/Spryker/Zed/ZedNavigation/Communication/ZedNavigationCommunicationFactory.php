<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ZedNavigation\Communication\Plugin\ZedNavigation;

/**
 * @method \Spryker\Zed\ZedNavigation\ZedNavigationConfig getConfig()
 * @method \Spryker\Zed\ZedNavigation\Business\ZedNavigationFacadeInterface getFacade()
 */
class ZedNavigationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ZedNavigation\Communication\Plugin\ZedNavigation
     */
    public function createZedNavigation(): ZedNavigation
    {
        return new ZedNavigation();
    }
}
