<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource\OrderSourceManager;

/**
 * @method \Spryker\Zed\ManualOrderEntry\ManualOrderEntryConfig getConfig()
 * @method \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryQueryContainerInterface getQueryContainer()
 */
class ManualOrderEntryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ManualOrderEntry\Business\Model\OrderSource\OrderSourceManagerInterface
     */
    public function createOrderSourceManager()
    {
        return new OrderSourceManager(
            $this->getQueryContainer()
        );
    }
}
